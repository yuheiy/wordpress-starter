const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const common = require("./webpack.config.js");

common.optimization.minimizer.push(new CssMinimizerPlugin());

module.exports = common;
