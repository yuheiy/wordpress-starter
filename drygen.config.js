/**
 * @type {import("drygen").UserConfig}
 */
module.exports = {
	rules: [
		...["blocks", "components"].map((type) => ({
			name: `scss/${type}`,
			dependencies: [
				`mytheme/assets/${type}/**/*.scss`,
				`!mytheme/assets/${type}/${type}.scss`,
			],
			outputs: [
				{
					path: `mytheme/assets/${type}/${type}.scss`,
					template: "mytheme/assets/styles/import.scss.ejs",
				},
			],
		})),
		...["settings", "tools", "objects", "scopes", "themes"].map((type) => ({
			name: `scss/${type}`,
			dependencies: [
				`mytheme/assets/styles/${type}/**/*.scss`,
				`!mytheme/assets/styles/${type}.scss`,
			],
			outputs: [
				{
					path: `mytheme/assets/styles/${type}.scss`,
					template: "mytheme/assets/styles/import.scss.ejs",
				},
			],
		})),
	],
};
