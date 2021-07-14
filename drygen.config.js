/**
 * @type {import("drygen").UserConfig}
 */
module.exports = {
	rules: [
		{
			name: "scss/components",
			dependencies: ["theme/assets/components/*/*.scss"],
			outputs: [
				{
					path: "theme/assets/components/components.scss",
					template: "theme/assets/styles/import.scss.ejs",
				},
			],
		},
		...["settings", "tools", "blocks", "objects", "scopes", "themes"].map(
			(type) => {
				const outputPath = `theme/assets/styles/${type}.scss`;
				return {
					name: `scss/${type}`,
					dependencies: [
						`theme/assets/styles/${type}/**/*.scss`,
						`!${outputPath}`,
					],
					outputs: [
						{
							path: outputPath,
							template: "theme/assets/styles/import.scss.ejs",
						},
					],
				};
			}
		),
	],
};
