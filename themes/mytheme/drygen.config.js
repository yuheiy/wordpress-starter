/**
 * @type {import("drygen").UserConfig}
 */
module.exports = {
	rules: [
		{
			name: "components/index.ts",
			dependencies: ["src/scripts/components/*.ts", "!src/scripts/components/index.ts"],
			outputs: [
				{
					path: "src/scripts/components/index.ts",
					template: "src/scripts/components/.import.ts.ejs",
				},
			],
		},
		{
			name: "stores/index.ts",
			dependencies: ["src/scripts/stores/*.ts", "!src/scripts/stores/index.ts"],
			outputs: [
				{
					path: "src/scripts/stores/index.ts",
					template: "src/scripts/stores/.import.ts.ejs",
				},
			],
		},
	],
};
