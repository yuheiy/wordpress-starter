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

const appElement: HTMLElement | null = document.querySelector("#app");
invariant(appElement);

invariant(appElement.dataset.route);
const App = routes.get(appElement.dataset.route);
invariant(App);

invariant(appElement.dataset.props);
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
