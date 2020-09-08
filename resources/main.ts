import "focus-visible";
import invariant from "tiny-invariant";
import { listen } from "quicklink";
import routes from "./routes";

// assign all asset files to `webpack-manifest.json`
require.context(
  ".",
  true,
  /\.(jpg|jpeg|png|gif|eot|otf|webp|svg|ttf|woff|woff2|mp4|webm|wav|mp3|m4a|aac|oga)$/
);

if (process.env.NODE_ENV !== "production") {
  console.log({
    NODE_ENV: process.env.NODE_ENV,
  });
}

const appElement = document.querySelector<HTMLElement>("#app");
invariant(appElement, "`#app` element must exist");

invariant(
  appElement.dataset.route,
  "`#app` element must have `data-route` attribute"
);
const App = routes.get(appElement.dataset.route);
invariant(App, `\`${appElement.dataset.route}\` does not exist in the routes`);

invariant(
  appElement.dataset.props,
  "`#app` element must have `data-props` attribute"
);
const props = JSON.parse(appElement.dataset.props);

const app = new App({
  target: appElement,
  props,
});

if (process.env.NODE_ENV !== "production") {
  (window as any).app = app;
}

window.addEventListener("load", () => {
  listen();
});
