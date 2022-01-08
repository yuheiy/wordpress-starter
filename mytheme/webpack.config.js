const defaultConfig = require("@wordpress/scripts/config/webpack.config");

module.exports = {
	...defaultConfig,
	resolve: {
		...defaultConfig.resolve,
		extensions: [".ts", ".tsx", "..."],
	},
	module: {
		...defaultConfig.module,
		// https://github.com/WordPress/gutenberg/pull/36260/files#diff-cba9f881720fd03020570ce36ae5b8dde43c14d4a1d79fa857155beb6ef578d1R156
		rules: defaultConfig.module.rules.map((rule) => {
			if (rule.test.toString() === /\.jsx?$/.toString()) {
				rule.test = /\.(j|t)sx?$/;
			}
			return rule;
		}),
	},
};
