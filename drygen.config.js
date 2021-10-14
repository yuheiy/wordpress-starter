/**
 * @type {import("drygen").UserConfig}
 */
module.exports = {
	rules: [
		{
			name: "scss/blocks",
			dependencies: ["mytheme/assets/scss/blocks/**/style.scss"],
			outputs: [
				{
					path: "mytheme/assets/scss/blocks.scss",
					template: "mytheme/assets/scss/blocks/import.scss.ejs",
				},
			],
		},
		{
			name: "scss/components",
			dependencies: ["mytheme/templates/components/*/*.scss"],
			outputs: [
				{
					path: "mytheme/templates/components/components.scss",
					template: "mytheme/assets/scss/import.scss.ejs",
				},
			],
		},
		{
			name: "ts/controllers",
			dependencies: [
				"mytheme/assets/ts/controllers/*.controller.ts",
				"mytheme/templates/components/*/*.controller.ts",
			],
			outputs: [
				{
					path: "mytheme/assets/ts/controllers/index.ts",
					template: "mytheme/assets/ts/controllers/import.ts.ejs",
				},
			],
		},
	],
};
