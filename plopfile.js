module.exports = (plop) => {
  plop.setHelper("includes", (array, element) => {
    return array.includes(element);
  });

  plop.setGenerator("c", {
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
          {
            name: "controller",
            checked: false,
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
          path: "resources/components/{{kebabCase name}}.scss",
          templateFile: "plop-templates/component/style.scss.hbs",
          skipIfExists: true,
        });
      }

      if (types.includes("controller")) {
        result.push({
          type: "add",
          path: "resources/components/{{kebabCase name}}.ts",
          templateFile: "plop-templates/component/controller.ts.hbs",
          skipIfExists: true,
        });
      }

      return result;
    },
  });
};
