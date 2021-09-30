/**
 * @type {import("drygen").UserConfig}
 */
module.exports = {
	rules: [
		...["style", "editor"].map((type) => ({
			name: `scss/blocks/${type}`,
			dependencies: [
				`mytheme/assets/blocks/**/${type}.scss`,
				`!mytheme/assets/blocks/${type}.scss`,
			],
			outputs: [
				{
					path: `mytheme/assets/blocks/${type}.scss`,
					template: "mytheme/assets/blocks/import.scss.ejs",
				},
			],
		})),
		{
			name: "scss/components",
			dependencies: [
				"mytheme/assets/components/**/*.scss",
				"!mytheme/assets/components/components.scss",
			],
			outputs: [
				{
					path: "mytheme/assets/components/components.scss",
					template: "mytheme/assets/styles/import.scss.ejs",
				},
			],
		},
	],
};
