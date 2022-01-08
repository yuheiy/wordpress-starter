// https://github.com/hotwired/stimulus-rails/blob/main/app/assets/javascripts/stimulus-autoloader.js

import { Application } from "@hotwired/stimulus";
import modules from "../controllers";

const application = Application.start();
application.debug = process.env.NODE_ENV !== "production";

const { controllerAttribute } = application.schema;
const registeredControllers = {};

function autoloadControllersWithin(element: Element) {
	queryControllerNamesWithin(element).forEach(loadController);
}

function queryControllerNamesWithin(element: Element) {
	return Array.from(element.querySelectorAll(`[${controllerAttribute}]`))
		.map(extractControllerNamesFrom)
		.flat();
}

function extractControllerNamesFrom(element: Element) {
	return element
		.getAttribute(controllerAttribute)
		.split(/\s+/)
		.filter((content) => content.length);
}

function loadController(name: string) {
	const fetchingModule = modules[name]();

	fetchingModule
		.then((module) => registerController(name, module))
		.catch((error) =>
			console.error(`Failed to autoload controller: ${name}`, error)
		);
}

function registerController(name: string, module: any) {
	if (name in registeredControllers) return;

	application.register(name, module.default);
	registeredControllers[name] = true;
}

new MutationObserver((mutationsList) => {
	for (const { attributeName, target, type } of mutationsList) {
		if (!(target instanceof Element)) continue;

		switch (type) {
			case "attributes": {
				if (
					attributeName == controllerAttribute &&
					target.getAttribute(controllerAttribute)
				) {
					extractControllerNamesFrom(target).forEach(loadController);
				}
			}
			case "childList": {
				autoloadControllersWithin(target);
			}
		}
	}
}).observe(document, {
	attributeFilter: [controllerAttribute],
	subtree: true,
	childList: true,
});

autoloadControllersWithin(document.documentElement);
