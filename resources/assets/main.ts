import "focus-visible";
import "wicg-inert";
import { Application } from "stimulus";
import { definitionsFromContext } from "./lib/stimulus-webpack-helpers";

declare global {
  interface HTMLElement {
    inert: boolean;
  }
}

require("../../node_modules/normalize.css/normalize.css");
require("./styles/base.scss");
importAll(require.context("./styles/utilities", false, /\.scss$/));
importAll(require.context("./components", false, /\.scss$/));

function importAll(r: __WebpackModuleApi.RequireContext) {
  r.keys().forEach(r);
}

// load all asset files to be passed to file-loader
require.context(
  ".",
  true,
  /\.(jpg|jpeg|png|gif|eot|otf|webp|svg|ttf|woff|woff2|mp4|webm|wav|mp3|m4a|aac|oga|ico)$/
);

if (process.env.NODE_ENV !== "production") {
  console.log({
    NODE_ENV: process.env.NODE_ENV,
  });
}

const application = Application.start();
application.load(
  definitionsFromContext(require.context("./controllers", false, /\.ts$/))
);

if (process.env.NODE_ENV !== "production") {
  (window as any).application = application;
}
