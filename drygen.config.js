module.exports = {
  rules: [
    {
      name: "sass-utilities",
      dependencies: [
        "resources/assets/styles/utilities/*.scss",
        "!resources/assets/styles/utilities/index.scss",
      ],
      outputs: [
        {
          path: "resources/assets/styles/utilities/index.scss",
          template: "resources/assets/styles/utilities/index.scss.hbs",
        },
      ],
    },
    {
      name: "sass-components",
      dependencies: [
        "resources/assets/components/*.scss",
        "!resources/assets/components/index.scss",
      ],
      outputs: [
        {
          path: "resources/assets/components/index.scss",
          template: "resources/assets/components/index.scss.hbs",
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
