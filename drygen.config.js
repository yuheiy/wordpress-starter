module.exports = {
	rules: [
		{
			name: "sass-imports",
			dependencies: {
				utility: ["resources/assets/styles/utilities/*.scss"],
				component: ["resources/assets/components/*.scss"],
			},
			outputs: [
				{
					path: "resources/assets/main.scss",
					template: "resources/assets/main.scss.hbs",
				},
			],
		},
		{
			name: "stimulus-controllers",
			dependencies: [
				"resources/assets/controllers/*.ts",
				"!resources/assets/controllers/index.ts",
			],
			outputs: [
				{
					path: "resources/assets/controllers/index.ts",
					template: "resources/assets/controllers/index.ts.hbs",
				},
			],
		},
	],
};
