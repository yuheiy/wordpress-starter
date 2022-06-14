const fs = require("node:fs");
const path = require("node:path");
const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const CopyWebpackPlugin = require("copy-webpack-plugin");
const SVGSpritemapPlugin = require("svg-spritemap-webpack-plugin");

const spriteTypes = fs.readdirSync(path.join(__dirname, "src/symbols"));

module.exports = {
	...defaultConfig,
	plugins: [
		...defaultConfig.plugins,

		new CopyWebpackPlugin({
			patterns: [
				{
					from: "**",
					to: "images/[path][name][ext]",
					context: "src/images",
				},
				{
					from: "**",
					to: "fonts/[path][name][ext]",
					context: "src/fonts",
					noErrorOnMissing: true,
				},
			],
		}),

		...spriteTypes.map(
			(type) =>
				new SVGSpritemapPlugin(`src/symbols/${type}/*.svg`, {
					output: {
						filename: `images/sprites/${type}.svg`,
					},
					sprite: {
						prefix: false,
						generate: {
							title: false,
						},
					},
				})
		),

		new CleanWebpackPlugin({
			cleanAfterEveryBuildPatterns: ["!fonts/**"],
		}),
	],
};
