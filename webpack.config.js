const defaultConfig = require("@wordpress/scripts/config/webpack.config");

const defaultJSRule = defaultConfig.module.rules.find(
	(rule) => rule.test.toString() === /\.jsx?$/.toString()
);

module.exports = {
	...defaultConfig,
	resolve: {
		...defaultConfig.resolve,
		extensions: [".tsx", ".ts", ".js"],
	},
	module: {
		...defaultConfig.module,
		rules: [
			{
				...defaultJSRule,
				test: /\.tsx?$/,
			},
			...defaultConfig.module.rules,
		],
	},
};
