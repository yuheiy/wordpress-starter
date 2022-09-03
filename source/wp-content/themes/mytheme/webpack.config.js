const fs = require("node:fs");
const path = require("node:path");
const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const SVGSpritemapPlugin = require("svg-spritemap-webpack-plugin");

const spriteTypes = fs.readdirSync(path.join(__dirname, "src/symbols"));

module.exports = {
	...defaultConfig,
	plugins: [
		...defaultConfig.plugins,

		...spriteTypes.map(
			(type) =>
				new SVGSpritemapPlugin(`src/symbols/${type}/*.svg`, {
					output: {
						filename: `sprites/${type}.svg`,
					},
					sprite: {
						prefix: false,
						generate: {
							title: false,
						},
					},
				})
		),
	],
};
