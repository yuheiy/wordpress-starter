import { defineConfig } from "vite";
import liveReload from "vite-plugin-live-reload";

export default defineConfig({
	plugins: [liveReload("mytheme/**/*.{php,twig}")],
	logLevel: "warn",
	build: {
		outDir: "mytheme/assets/build",
		assetsDir: ".",
		rollupOptions: {
			input: "mytheme/assets/main.ts",
		},
		manifest: true,
		terserOptions: {
			// https://github.com/github/catalyst/issues/98
			mangle: false,
		},
	},
});
