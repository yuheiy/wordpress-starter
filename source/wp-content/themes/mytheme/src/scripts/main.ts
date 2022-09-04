import "focus-options-polyfill";
import "focus-visible";
import "wicg-inert";
import "./stores";
import Alpine from "alpinejs";
import components from "./components";
import stores from "./stores";

declare global {
	interface HTMLElement {
		inert: boolean;
	}
}

if (process.env.NODE_ENV !== "production") {
	console.log({ NODE_ENV: process.env.NODE_ENV });
}

Alpine.plugin(components);
Alpine.plugin(stores);

(window as any).Alpine = Alpine;
Alpine.start();