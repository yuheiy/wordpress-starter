module.exports = (plop) => {
	plop.setGenerator("ct", {
		description: "component",
		prompts: [
			{
				type: "input",
				name: "name",
			},
			{
				type: "checkbox",
				name: "types",
				choices: [
					{
						name: "template",
						checked: false,
					},
					{
						name: "style",
						checked: true,
					},
				],
			},
		],
		actions: ({ types }) => {
			const result = [];

			if (types.includes("template")) {
				result.push({
					type: "add",
					path: "my-theme/templates/partial/{{kebabCase name}}.twig",
					templateFile: "plop-templates/component/template.twig.hbs",
					skipIfExists: true,
				});
			}

			if (types.includes("style")) {
				result.push({
					type: "add",
					path: "resources/assets/components/{{kebabCase name}}.scss",
					templateFile: "plop-templates/component/style.scss.hbs",
					skipIfExists: true,
				});
			}

			return result;
		},
	});

	plop.setGenerator("cr", {
		description: "controller",
		prompts: [
			{
				type: "input",
				name: "name",
			},
		],
		actions: [
			{
				type: "add",
				path: "resources/assets/controllers/{{kebabCase name}}.ts",
				templateFile: "plop-templates/controller/controller.ts.hbs",
			},
		],
	});
};
