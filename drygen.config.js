/**
 * @type {import("drygen").UserConfig}
 */
module.exports = {
	rules: [
		...["blocks", "components"].map((type) => ({
			name: `scss/${type}`,
			dependencies: [
				`theme/assets/${type}/**/*.scss`,
				`!theme/assets/${type}/${type}.scss`,
			],
			outputs: [
				{
					path: `theme/assets/${type}/${type}.scss`,
					template: "theme/assets/styles/import.scss.ejs",
				},
			],
		})),
		...["settings", "tools", "objects", "scopes", "themes"].map((type) => ({
			name: `scss/${type}`,
			dependencies: [
				`theme/assets/styles/${type}/**/*.scss`,
				`!theme/assets/styles/${type}.scss`,
			],
			outputs: [
				{
					path: `theme/assets/styles/${type}.scss`,
					template: "theme/assets/styles/import.scss.ejs",
				},
			],
		})),
	],
};
