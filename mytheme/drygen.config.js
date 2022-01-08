/**
 * @type {import("drygen").UserConfig}
 */
module.exports = {
	rules: [
		{
			name: "scss/blocks",
			dependencies: ["assets/scss/blocks/**/style.scss"],
			outputs: [
				{
					path: "assets/scss/blocks.scss",
					template: "assets/scss/blocks/import.scss.ejs",
				},
			],
		},
		{
			name: "scss/components",
			dependencies: ["templates/components/*/*.scss"],
			outputs: [
				{
					path: "templates/components/components.scss",
					template: "assets/scss/import.scss.ejs",
				},
			],
		},
		{
			name: "ts/controllers",
			dependencies: [
				"assets/ts/controllers/*.controller.ts",
				"templates/components/*/*.controller.ts",
			],
			outputs: [
				{
					path: "assets/ts/controllers/index.ts",
					template: "assets/ts/controllers/import.ts.ejs",
				},
			],
		},
	],
};
