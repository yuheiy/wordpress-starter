import "focus-options-polyfill";
import "focus-visible";
import "wicg-inert";
import "./stores";
import Alpine from "alpinejs";
import components from "./components";

declare global {
	interface HTMLElement {
		inert: boolean;
	}
}

if (process.env.NODE_ENV !== "production") {
	console.log({ NODE_ENV: process.env.NODE_ENV });
}

Alpine.plugin(components);

(window as any).Alpine = Alpine;
Alpine.start();
