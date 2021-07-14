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
			(type) => ({
				name: `scss/${type}`,
				dependencies: [`theme/assets/styles/${type}/*.scss`],
				outputs: [
					{
						path: `theme/assets/styles/${type}.scss`,
						template: "theme/assets/styles/import.scss.ejs",
					},
				],
			})
		),
	],
};
