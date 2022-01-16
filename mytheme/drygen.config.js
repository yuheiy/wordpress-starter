/**
 * @type {import("drygen").UserConfig}
 */
module.exports = {
	rules: [
		{
			name: "controllers",
			dependencies: ["src/scripts/controllers/*.controller.ts"],
			outputs: [
				{
					path: "src/scripts/controllers/index.ts",
					template: "src/scripts/controllers/import.ts.ejs",
				},
			],
		},
	],
};