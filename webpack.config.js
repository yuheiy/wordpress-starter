const path = require("path");
const sveltePreprocess = require("svelte-preprocess");
const autoprefixer = require("autoprefixer");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const TerserJSPlugin = require("terser-webpack-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const ManifestPlugin = require("webpack-manifest-plugin");
const { BundleAnalyzerPlugin } = require("webpack-bundle-analyzer");
const getPort = require("get-port");
const address = require("address");

const isDev = process.env.NODE_ENV !== "production";

const wordpressPort = 8888;
const wordpressLocalHost = `localhost:${wordpressPort}`;
const wordpressLocalOrigin = `http://${wordpressLocalHost}`;

module.exports = async () => {
  const webpackPort = isDev && (await getPort({ port: 3000 }));
  const webpackNetworkHost = webpackPort && `${address.ip()}:${webpackPort}`;
  const webpackNetworkOrigin = webpackPort && `http://${webpackNetworkHost}`;

  return {
    context: path.join(__dirname, "resources"),
    entry: "./main.ts",
    output: {
      path: path.join(__dirname, "my-theme", "assets"),
      filename: isDev ? "[name].js" : "[name].[contenthash:8].js",
      chunkFilename: isDev
        ? "[name].chunk.js"
        : "[name].chunk.[contenthash:8].js",
      publicPath: "/wp-content/themes/my-theme/assets/",
    },
    module: {
      rules: [
        {
          test: /\.ts$/,
          use: [
            {
              loader: "ts-loader",
              options: {
                transpileOnly: true,
              },
            },
          ],
        },
        {
          test: /\.svelte$/,
          use: [
            {
              loader: "svelte-loader",
              options: {
                preprocess: sveltePreprocess({
                  postcss: {
                    plugins: [autoprefixer({ cascade: false })],
                  },
                }),
                emitCss: true,
                hotReload: false, // pending https://github.com/sveltejs/svelte/issues/622
              },
            },
          ],
        },
        {
          test: /\.css$/,
          use: [
            isDev ? "style-loader" : MiniCssExtractPlugin.loader,
            "css-loader",
          ],
        },
        {
          exclude: [/\.(js|mjs|ts)$/, /\.json$/, /\.svelte$/, /\.css$/],
          use: [
            {
              loader: "file-loader",
              options: {
                name: isDev
                  ? "[path][name].[ext]"
                  : "[path][name].[contenthash:8].[ext]",
              },
            },
          ],
        },
      ],
    },
    resolve: {
      alias: {
        svelte: path.join(__dirname, "node_modules", "svelte"),
      },
      extensions: [".js", ".ts"],
      mainFields: ["svelte", "browser", "module", "main"],
    },
    devtool: isDev && "cheap-module-eval-source-map",
    optimization: {
      minimizer: [new TerserJSPlugin(), new OptimizeCSSAssetsPlugin()],
    },
    plugins: [
      new ManifestPlugin({
        fileName: "webpack-manifest.json",
        writeToFileEmit: true,
      }),
      !isDev &&
        new MiniCssExtractPlugin({
          filename: "[name].[contenthash:8].css",
        }),
      process.env.ANALYZE === "true" &&
        !isDev &&
        new BundleAnalyzerPlugin({
          analyzerMode: "static",
          reportFilename: path.join(__dirname, "resources", "analyze.html"),
        }),
    ].filter(Boolean),
    devServer: {
      compress: true,
      clientLogLevel: "silent",
      overlay: true,
      hot: true,
      contentBase: path.join(__dirname, "my-theme"),
      contentBasePublicPath: "/wp-content/themes/my-theme",
      watchContentBase: true,
      watchOptions: {
        ignored: path.join(__dirname, "my-theme", "assets", "**"),
      },
      proxy: {
        "/": {
          target: wordpressLocalOrigin,
          changeOrigin: true,
        },
      },
      transportMode: "ws",
      stats: "errors-only",
      host: "0.0.0.0",
      port: webpackPort,
      public: webpackNetworkHost,
      headers: {
        "Access-Control-Allow-Origin": webpackNetworkOrigin,
      },
    },
  };
};
