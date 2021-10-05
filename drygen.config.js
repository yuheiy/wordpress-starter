/**
 * @type {import("drygen").UserConfig}
 */
module.exports = {
	rules: [
		...["style", "editor"].map((type) => ({
			name: `scss/blocks/${type}`,
			dependencies: [
				`mytheme/assets/styles/blocks/**/${type}.scss`,
				`!mytheme/assets/styles/blocks/${type}.scss`,
			],
			outputs: [
				{
					path: `mytheme/assets/styles/blocks/${type}.scss`,
					template: "mytheme/assets/styles/blocks/import.scss.ejs",
				},
			],
		})),
		{
			name: "scss/components",
			dependencies: [
				"mytheme/templates/components/**/*.scss",
				"!mytheme/templates/components/components.scss",
			],
			outputs: [
				{
					path: "mytheme/templates/components/components.scss",
					template: "mytheme/assets/styles/import.scss.ejs",
				},
			],
		},
	],
};
