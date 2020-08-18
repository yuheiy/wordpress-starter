import path from "path";

const definitionsFromContext = (context: __WebpackModuleApi.RequireContext) => {
  const result = new Map();

  context.keys().forEach((key) => {
    const name = path.basename(key, path.extname(key));
    const module = context(key).default as any;
    result.set(name, module);
  });

  return result;
};

export const routes = definitionsFromContext(
  require.context(".", false, /\.svelte$/)
);
