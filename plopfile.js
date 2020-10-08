module.exports = (plop) => {
  plop.setGenerator("r", {
    description: "route",
    prompts: [
      {
        type: "input",
        name: "name",
      },
    ],
    actions: [
      {
        type: "add",
        path: "my-theme/{{name}}.php",
        templateFile: "plop-templates/route/wordpress.hbs",
        skipIfExists: true,
      },
      {
        type: "add",
        path: "resources/routes/{{name}}.svelte",
        templateFile: "plop-templates/route/svelte.hbs",
        skipIfExists: true,
      },
    ],
  });
};
