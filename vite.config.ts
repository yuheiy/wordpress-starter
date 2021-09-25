import { visualizer } from "rollup-plugin-visualizer";
import { defineConfig } from "vite";
import liveReload from "vite-plugin-live-reload";

export default defineConfig({
	plugins: [liveReload("mytheme/**/*.{php,twig}"), visualizer()],
	logLevel: "warn",
	build: {
		outDir: "mytheme/assets/build",
		assetsDir: ".",
		rollupOptions: {
			input: "mytheme/assets/main.ts",
		},
		manifest: true,
	},
});
