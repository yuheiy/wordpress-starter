---
to: mytheme/assets/components/form/form.controller.ts
---
import { controller } from "@github/catalyst";
import { BaseFormElement } from "./inc/base.controller";

@controller
export class CFormElement extends BaseFormElement {
	connectedCallback() {
		super.connectedCallback();
	}

	// sample
	beforeSend() {
		// console.log("beforeSend");
		return new Promise((resolve) => {
			resolve(null);
		});
	}

	afterSend() {
		// console.log("afterSend");
		return new Promise((resolve) => {
			resolve(null);
		});
	}

	beforeChangeFieldsView() {
		// console.log("beforeChangeFieldsView");
		return new Promise((resolve) => {
			this.stage.addEventListener(
				"transitionend",
				() => {
					resolve(null);
				},
				{ once: true }
			);
			this.stage.classList.add("is-hidden");
		});
	}

	afterChangeFieldsView() {
		// console.log("afterChangeFieldsView");
		return new Promise((resolve) => {
			this.stage.addEventListener(
				"transitionend",
				() => {
					resolve(null);
				},
				{ once: true }
			);
			this.stage.classList.remove("is-hidden");
		});
	}
}


