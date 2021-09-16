---
to: mytheme/assets/components/form/inc/base.controller.ts
---
import { attr, controller, target, targets } from "@github/catalyst";
import { createForm, FieldState, FormApi, FormState } from "final-form";
import invariant from "tiny-invariant";
import { FormConfigItem } from "./form.config";
import formConfig from "./form.config.json";
import {
	isEmpty,
	maxLength,
	minLength,
	PAT_EMAIL,
	PAT_PHONENUMBER,
} from "./utils";

type InputElement = HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement;

type Constructor<T = {}> = new (...args: any[]) => T;

function Hooks<TBase extends Constructor>(Base: TBase) {
	return class extends Base {
		beforeSend() {
			return new Promise((resolve) => resolve(null));
		}
		afterSend() {
			return new Promise((resolve) => resolve(null));
		}

		beforeChangeFieldsView() {
			return new Promise((resolve) => resolve(null));
		}

		afterChangeFieldsView() {
			return new Promise((resolve) => resolve(null));
		}
	};
}

@controller
export class BaseFormElement extends Hooks(HTMLElement) {
	static handleErrorMessage(errorElement, show, error) {
		if (errorElement) {
			if (show) {
				errorElement.innerHTML = error;
				errorElement.style.display = "block";
			} else {
				errorElement.innerHTML = "";
				errorElement.style.display = "none";
			}
		}
	}

	static getCheckBoxRawValues(values, data) {
		return data.filter((_, index) => values[index]);
	}

	static getRawName(name) {
		return name.replace(/\[.*?]/g, "");
	}

	static getCustomErrorMassage(name, validation) {
		if (/:/.test(validation)) {
			return (
				formConfig.error_messages[`${name}.${validation}`] ||
				formConfig.error_messages[
					`${name}.${validation.substring(0, validation.indexOf(":"))}`
				] ||
				formConfig.error_messages.base[validation] ||
				formConfig.error_messages.base[
					validation.substring(0, validation.indexOf(":"))
				]
			);
		} else {
			return (
				formConfig.error_messages[`${name}.${validation}`] ||
				formConfig.error_messages.base[validation]
			);
		}
	}

	static getValue(value, config: FormConfigItem) {
		const data = config?.options?.data;
		if (data) {
			if (config.type === "checkbox") {
				return BaseFormElement.getCheckBoxRawValues(value, data)
					.map((d) => d.label)
					.join(", ");
			}

			return data
				.filter((d) => d.value === value)
				.map((d) => d.label)
				.join(", ");
		} else {
			return value;
		}
	}

	@target stage: HTMLElement;
	@target fieldInput: HTMLTemplateElement;
	@target fieldConfirm: HTMLTemplateElement;
	@target fieldComplete: HTMLTemplateElement;
	@target fieldError: HTMLTemplateElement;
	@targets inputs: InputElement[];
	@targets outputs: HTMLElement[];
	@attr mode: "fieldInput" | "fieldConfirm" | "fieldComplete" | "fieldError" =
		"fieldInput";

	back() {
		this.mode = "fieldInput";
	}

	async confirm(e) {
		e.preventDefault();
		await this.form.submit();
		this.mode = "fieldConfirm";
	}

	private injectValuesToView() {
		this.outputs.forEach((output) => {
			const name = output.dataset.name;
			const value = this.bufferValues[name];
			const config = this.getItemConfig(name);
			const isArray = Array.isArray(value);

			if (config.validates.some((v) => v.indexOf("same") !== -1)) {
				output.closest("[data-form-item]").remove();
				return;
			}

			if ((isArray && value.some((v) => v)) || (!isArray && value)) {
				output.innerText = BaseFormElement.getValue(value, config);
			} else {
				output.closest("[data-form-item]").remove();
			}
		});
	}

	async submit(e) {
		e.preventDefault();
		await this.form.submit();
		await this.beforeSend();
		try {
			const json = await (await this.send()).json();
			console.log(json);
			this.mode = "fieldComplete";
			await this.afterSend();
		} catch (e) {
			this.mode = "fieldError";
		}
	}

	send() {
		const buffer = this.bufferValues;
		invariant(!!buffer);

		const values = formConfig.items
			.filter((item) => !!buffer[item.name] && !this.unsubscribing[item.name])
			.filter((item) => !item.validates.some((v) => v.indexOf("same") !== -1))
			.map((item) => {
				return {
					label: item.label,
					value: BaseFormElement.getValue(
						buffer[item.name],
						item as FormConfigItem
					),
				};
			});

		const jsonBody = formConfig.post_constants.reduce(
			(previousValue, currentValue, currentIndex) => {
				const constantValue = buffer[currentValue];
				invariant(constantValue);
				previousValue[currentValue] = constantValue;
				return previousValue;
			},
			{
				values,
			}
		);

		return fetch(formConfig.post, {
			method: "POST",
			headers: {
				"Content-Type": "Content-Type: application/json",
			},
			body: JSON.stringify(jsonBody),
		});
	}

	// for debug
	changeView(e) {
		this.mode = e.target.getAttribute("data-key");
	}

	connectedCallback() {}

	disconnectedCallback() {}

	async attributeChangedCallback(
		name: string,
		oldValue: string | null,
		newValue: string | null
	) {
		const field = this[newValue];
		if (oldValue === newValue || !field) {
			return;
		}

		if (oldValue) {
			await this.beforeChangeFieldsView();
			this.querySelector("#" + oldValue).remove();
		}

		const clone = field.content.cloneNode(true);
		this.stage.appendChild(clone);

		if (newValue === "fieldConfirm") {
			this.injectValuesToView();
		}

		if (!oldValue && newValue === "fieldInput") {
			this.initForm(formConfig.initialValues);
		}

		if (
			["fieldConfirm", "fieldComplete"].indexOf(oldValue) !== -1 &&
			newValue === "fieldInput"
		) {
			this.initForm(this.bufferValues);
		}

		if (oldValue) {
			await this.afterChangeFieldsView();
		}
	}

	private bufferValues = {};
	private hiddenItemNamesForInit = [];
	private form: FormApi<unknown, Partial<unknown>>;

	/**
	 *
	 * @private
	 * Allow duplicate keys
	 */
	private hiddenItemNames = [];

	/**
	 *
	 * @private
	 * For detection unsubscribing items. use raw config name
	 */
	private unsubscribing = [];

	/**
	 *
	 * @private
	 * For call unsubscribe. use formatted name.
	 */
	private unsubscribes = {};

	// Check initialValue(bufferValues)
	private setHiddenItemNames(initialValues) {
		return [
			...formConfig.items
				.filter(({ options }) => options?.depended_on)
				.map(({ name, options, type }) => {
					const initialValue = initialValues[name];
					const hiddenItems = options.depended_on.filter((i) => {
						// If it returns 'true' that means hidden item.
						if (Array.isArray(initialValue)) {
							return !BaseFormElement.getCheckBoxRawValues(
								initialValue,
								options.data
							)
								.map((d) => d.value)
								.some((e) => i.value.indexOf(e) !== -1);
						} else {
							return i.value.indexOf(initialValue) === -1;
						}
					});

					if (hiddenItems.length) {
						return hiddenItems
							.map(({ targets }) => {
								return this.formatHiddenItems(targets).flat();
							})
							.flat();
					}
				})
				// Filter empty array
				// If all hidden items is shown, the result contain empty array
				// So, 'Filter empty array'. That's it.
				.filter((item) => item)
				.flat(),
		];
	}

	private setUnsubscribing() {
		return [
			...new Set(
				this.hiddenItemNames.map((i) => {
					return BaseFormElement.getRawName(i);
				})
			),
		].reduce((previousValue, currentValue, currentIndex) => {
			previousValue[currentValue] = true;
			return previousValue;
		}, {});
	}

	private formatHiddenItems(targets) {
		// find multiple types
		return targets.map((target) => {
			const item = formConfig.items.find(({ name }) => name === target);
			if (item && item.type === "checkbox") {
				return item.options.data.map((_, index) => `${target}[${index}]`);
			}

			if (item && item.type === "radio") {
				return item.options.data.map(() => `${target}`);
			}

			return target;
		});
	}

	private initForm(initialValues) {
		this.hiddenItemNames = this.setHiddenItemNames(initialValues);
		this.unsubscribing = this.setUnsubscribing();
		this.hiddenItemNamesForInit = [...this.hiddenItemNames];

		this.form = createForm<unknown>({
			onSubmit: (values) => {
				this.bufferValues = values;
			},
			validate: this.validate.bind(this),
			initialValues,
		});

		this.inputs.forEach((input) => {
			this.registerField(input, this.getItemConfig(input.name));
		});

		this.form.subscribe(
			(formState) => {
				this.toggleSubscription(formState);
			},
			{
				// FormSubscription: the list of values you want to be updated about
				dirty: true,
				// valid: true,
				values: true,
				// submitSucceeded: true,
			}
		);
	}

	private validate(values) {
		const errors = {};
		formConfig.items.forEach(({ name, type, validates, options }) => {
			if (this.unsubscribing[name]) {
				return;
			}
			const target = values[name];

			// require
			if (validates.indexOf("require") !== -1) {
				const errorMassage = BaseFormElement.getCustomErrorMassage(
					name,
					"require"
				);
				if (type === "checkbox") {
					if (
						!target ||
						(Array.isArray(values[name]) && !values[name].some((v) => v))
					) {
						errors[name] = options.data.map(() => errorMassage);
					}
				} else if (!target || (target && isEmpty(String(target).trim()))) {
					errors[name] = [errorMassage];
				}
			}

			// pattern
			// - email
			if (target && validates.indexOf("email") !== -1) {
				const errorMassage = BaseFormElement.getCustomErrorMassage(
					name,
					"email"
				);
				if (!PAT_EMAIL.test(target)) {
					errors[name] = [errorMassage];
				}
			}

			// - phone
			if (target && validates.indexOf("phone") !== -1) {
				const errorMassage = BaseFormElement.getCustomErrorMassage(
					name,
					"phone"
				);
				if (!PAT_PHONENUMBER.test(target)) {
					errors[name] = [errorMassage];
				}
			}

			// length
			// - min
			const min = validates.find((v) => /^min/.test(v));
			if (target && min) {
				const length = min.substring(min.indexOf(":") + 1);
				invariant(length, "Missed length property, Check your config file.");
				const errorMassage = BaseFormElement.getCustomErrorMassage(name, min);
				if (!minLength(target, length as unknown as number)) {
					errors[name] = [errorMassage];
				}
			}

			// - max
			const max = validates.find((v) => /^max/.test(v));
			if (target && max) {
				const length = max.substring(max.indexOf(":") + 1);
				invariant(length, "Missed length property, Check your config file.");
				const errorMassage = BaseFormElement.getCustomErrorMassage(name, max);
				if (!maxLength(target, length as unknown as number)) {
					errors[name] = [errorMassage];
				}
			}

			// same
			const same = validates.find((v) => /^same/.test(v));
			if (target && same) {
				const comparisonFrom = same.substring(same.indexOf(":") + 1);
				invariant(
					comparisonFrom,
					"Missed length property, Check your config file."
				);
				const errorMassage = BaseFormElement.getCustomErrorMassage(name, same);
				if (target !== values[comparisonFrom]) {
					errors[name] = [errorMassage];
				}
			}
		});
		return errors;
	}

	private getItemConfig(name: string): FormConfigItem {
		return formConfig.items.find(
			(item) => item.name === BaseFormElement.getRawName(name)
		) as FormConfigItem;
	}

	private toggleSubscription(formState: FormState<unknown, Partial<unknown>>) {
		const inputs = this.inputs;
		const names = Object.keys(formState.values);
		const getName = BaseFormElement.getRawName;

		// for checkbox or some else
		const multiRegisteredFields = (value, config) => {
			const depended_on = config.options.depended_on;
			const depended_values = depended_on.map((d) => d.value).flat();
			const depended_targets = depended_on.map((d) => d.targets).flat();
			const data = config.options.data;

			const isSubscribeItem = BaseFormElement.getCheckBoxRawValues(value, data)
				.map((d) => d.value)
				.some((e) => depended_values.indexOf(e) !== -1);
			if (isSubscribeItem) {
				inputs
					.filter(({ name }) => depended_targets.indexOf(getName(name)) !== -1)
					.forEach((item) => {
						const itemName = getName(item.name);
						this.unsubscribing[itemName] = false;
						this.registerField(item, this.getItemConfig(itemName));
					});
			} else {
				depended_targets.forEach((name) => {
					const targetConfig = this.getItemConfig(name);
					if (targetConfig.type === "checkbox") {
					} else {
						this.unsubscribing[getName(name)] = true;
						this.unsubscribes[name]();
					}
				});
			}
		};

		const singleRegisteredFields = (value, config) => {
			const depended_on = config.options.depended_on;
			depended_on.forEach((item) => {
				if (item.value.indexOf(value) === -1) {
					// unsubscribe
					Object.keys(this.unsubscribes)
						.filter((name) => item.targets.indexOf(getName(name)) !== -1)
						.forEach((name) => {
							this.unsubscribing[getName(name)] = true;
							this.unsubscribes[name]();
						});
				} else {
					// subscribe
					const subscribeItems = inputs.filter(
						({ name }) => item.targets.indexOf(getName(name)) !== -1
					);
					subscribeItems.forEach((item) => {
						const itemName = getName(item.name);
						this.unsubscribing[itemName] = false;
						this.registerField(item, this.getItemConfig(itemName));
					});
				}
			});
		};

		names
			.filter((name) => this.getItemConfig(name)?.options?.depended_on)
			.map((name) => {
				const config = this.getItemConfig(name);
				const value = formState.values[name];
				if (Array.isArray(value)) {
					multiRegisteredFields(value, config);
				} else {
					singleRegisteredFields(value, config);
				}
			});
	}

	private registerField(input: InputElement, config: FormConfigItem) {
		const { name, type } = input;
		const parent: HTMLElement = input.closest("[data-form-item]");

		// for initial build
		const hiddenItemNamesIndex = this.hiddenItemNamesForInit.indexOf(name);
		if (hiddenItemNamesIndex !== -1) {
			parent.style.display = "none";
			this.hiddenItemNamesForInit.splice(hiddenItemNamesIndex, 1);
			return;
		}

		// show item
		parent.style.display = "";

		const registerEventListeners = (
			input: InputElement,
			fieldState: FieldState<never>
		) => {
			const { blur, change, focus } = fieldState;
			const registered = input.getAttribute("data-registration");
			if (!registered) {
				// first time, register event listeners
				input.addEventListener("blur", () => blur());
				input.addEventListener("input", (e) => {
					const target = <HTMLInputElement>e.target;
					// @ts-ignore
					change(type === "checkbox" ? target.checked : target.value);
				});
				input.addEventListener("focus", () => focus());
				input.setAttribute("data-registration", "registered");
			}
		};

		const updateStateValue = (
			input: InputElement,
			fieldState: FieldState<never>
		) => {
			const { value } = fieldState;
			if (type === "checkbox") {
				(<HTMLInputElement>input).checked = !!value;
			} else if (type === "radio") {
				(<HTMLInputElement>input).checked = value === input.value;
			} else {
				input.value = value === undefined ? "" : value;
			}
		};

		const subscription = {
			value: true,
			error: true,
			touched: true,
		};

		const unsubscribe = this.form.registerField(
			// @ts-ignore
			name,
			(fieldState) => {
				const { error, touched } = fieldState;

				updateStateValue(input, fieldState);
				registerEventListeners(input, fieldState);

				// show/hide errors
				BaseFormElement.handleErrorMessage(
					parent.querySelector(
						`[data-error="${BaseFormElement.getRawName(name)}"]`
					) as HTMLElement,
					touched && error,
					error
				);
			},
			subscription
		);

		this.unsubscribes[name] = () => {
			// @ts-ignore
			this.form.change(name, null);
			parent.style.display = "none";
			input.removeAttribute("data-registration");
			unsubscribe();
		};
	}
}
