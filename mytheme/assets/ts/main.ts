import "../scss/main.scss";

// https://vitejs.dev/guide/env-and-mode.html#env-variables
if (import.meta.env.DEV) {
	console.log({
		PROD: import.meta.env.PROD,
		DEV: import.meta.env.DEV,
	});
}
