import "../scss/main.scss";

// https://vitejs.dev/guide/env-and-mode.html#env-variables
if (process.env.NODE_ENV === "development") {
	console.log({ NODE_ENV: process.env.NODE_ENV });
}
