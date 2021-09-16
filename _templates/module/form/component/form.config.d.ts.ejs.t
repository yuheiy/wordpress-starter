---
to: mytheme/assets/components/form/inc/form.config.d.ts
---
export type FormConfigItemType =
	| "text"
	| "address"
	| "textarea"
	| "tel"
	| "email"
	| "number"
	| "checkbox"
	| "radio"
	// | "datepicker"
	| "select";

export type FormConfigItem = {
	label: string;
	name: string;
	type: FormConfigItemType;
	placeholder: string;
	autocomplete: "on" | "off";
	initialValues: {
		[key: string]: string;
	};
	validates: string[];
	options: {
		data?: {
			value: string;
			label: string;
		}[];
		depended_on?: {
			targets: string[];
			value: string[];
		}[];
	};
};

export interface FormConfig {
	debug: boolean;
	from: {
		email: string;
		name: string;
	};
	admins: string[];
	subjects: string;
	site_name: string;

	post: string;
	post_constants: string[];
	has_confirm: boolean;
	error_messages: {
		base: {
			[key: string]: string;
		};
		[key: string]: string;
	};
	controller: string;
	items: FormConfigItem[];
}

declare module "*/form.config.json" {
	const value: FormConfig;
	export default value;
}
