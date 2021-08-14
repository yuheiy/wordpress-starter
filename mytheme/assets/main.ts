import "./main.scss";

// to prevent Vite’s syntax errors, enclosed in square brackets
[
	import.meta.globEager("./blocks/**/script.ts"),
	import.meta.globEager("./{components,controllers}/**/*.controller.ts"),
];

// https://vitejs.dev/guide/env-and-mode.html#env-variables
if (import.meta.env.DEV) {
	console.log({
		PROD: import.meta.env.PROD,
		DEV: import.meta.env.DEV,
	});
}
