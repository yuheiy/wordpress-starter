import { visualizer } from "rollup-plugin-visualizer";
import { defineConfig } from "vite";
import liveReload from "vite-plugin-live-reload";

export default defineConfig({
	plugins: [liveReload("mytheme/**/*.twig"), visualizer()],
	logLevel: "warn",
	build: {
		outDir: "mytheme/build",
		assetsDir: ".",
		rollupOptions: {
			input: "mytheme/assets/ts/main.ts",
		},
		manifest: true,
	},
});
