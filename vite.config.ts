import { defineConfig } from "vite";
import liveReload from "vite-plugin-live-reload";

export default defineConfig({
	plugins: [liveReload("theme/**/*.{php,twig}")],
	logLevel: "warn",
	build: {
		outDir: "theme/build",
		assetsDir: ".",
		rollupOptions: {
			input: "theme/assets/main.ts",
		},
		manifest: true,
		terserOptions: {
			// https://github.com/github/catalyst/issues/98
			mangle: false,
		},
	},
});
