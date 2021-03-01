const path = require("path");
const sass = require("sass");
const Fiber = require("fibers");
const autoprefixer = require("autoprefixer");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const TerserJSPlugin = require("terser-webpack-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const { WebpackManifestPlugin } = require("webpack-manifest-plugin");
const detectPort = require("detect-port");
const address = require("address");

const wordpressPort = 8888;
const wordpressLocalHost = `localhost:${wordpressPort}`;
const wordpressLocalOrigin = `http://${wordpressLocalHost}`;

module.exports = async (env) => {
	const isDev = Boolean(env.WEBPACK_SERVE);
	const webpackPort = isDev && (await detectPort(3000));
	const webpackNetworkHost = webpackPort && `${address.ip()}:${webpackPort}`;
	const webpackNetworkOrigin = webpackPort && `http://${webpackNetworkHost}`;

	return {
		mode: isDev ? "development" : "production",
		context: path.join(__dirname, "resources", "assets"),
		entry: "./main.js",
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
					test: /\.m?js$/,
					include: path.join(__dirname, "resources", "assets"),
					use: {
						loader: "babel-loader",
						options: {
							assumptions: {
								setPublicClassFields: true,
								privateFieldsAsProperties: true,
							},
							plugins: ["@babel/plugin-proposal-class-properties"],
							cacheDirectory: true,
						},
					},
				},
				{
					test: /\.scss$/,
					use: [
						isDev ? "style-loader" : MiniCssExtractPlugin.loader,
						"css-loader",
						{
							loader: "postcss-loader",
							options: {
								postcssOptions: {
									plugins: [
										autoprefixer({
											cascade: false,
										}),
									],
								},
							},
						},
						"resolve-url-loader",
						{
							loader: "sass-loader",
							options: {
								implementation: sass,
								sassOptions: {
									fiber: Fiber,
								},
								sourceMap: true, // required for resolve-url-loader
							},
						},
					],
				},
				{
					exclude: [/\.m?js$/, /\.json$/, /\.scss$/],
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
		devtool: isDev && "cheap-module-eval-source-map",
		optimization: {
			minimizer: [new TerserJSPlugin(), new CssMinimizerPlugin()],
		},
		plugins: [
			new WebpackManifestPlugin({
				fileName: "webpack-manifest.json",
				writeToFileEmit: true,
			}),
			!isDev &&
				new MiniCssExtractPlugin({
					filename: "[name].[contenthash:8].css",
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
			stats: "errors-warnings",
			host: "0.0.0.0",
			port: webpackPort,
			public: webpackNetworkHost,
			headers: {
				"Access-Control-Allow-Origin": webpackNetworkOrigin,
			},
		},
	};
};
