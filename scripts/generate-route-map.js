const fs = require("fs");
const path = require("path");
const globby = require("globby");
const chokidar = require("chokidar");

const rootDir = path.join(__dirname, "..");
const routesDir = path.join(rootDir, "resources", "routes");
const routesFilePath = path.join(routesDir, "index.ts");

const readRouteMap = async () => {
  const filePaths = await globby(path.join(routesDir, "*.svelte"));
  return filePaths.map((filePath) => {
    const { name } = path.parse(filePath);
    const filename = path.relative(routesDir, filePath);
    return { name, filename };
  });
};

const indent = (lines) => {
  return lines
    .split("\n")
    .map((line) => " ".repeat(2) + line)
    .join("\n");
};

const generate = (routeMap) => {
  return `export default new Map([
${routeMap
  .map(({ name, filename }) => {
    return indent(`[
  "${name}",
  async () =>
    (
      await import(
        /* webpackChunkName: "${name}" */ "./${filename}"
      )
    ).default,
],`);
  })
  .join("\n")}
]);
`;
};

const write = async () => {
  const routeMap = await readRouteMap();
  const content = generate(routeMap);
  await fs.promises.writeFile(routesFilePath, content);
};

const main = async () => {
  await write();

  const isDev = process.env.NODE_ENV !== "production";
  if (isDev) {
    chokidar
      .watch([routesDir, `!${routesFilePath}`], { ignoreInitial: true })
      .on("all", write);
  }
};

main();
