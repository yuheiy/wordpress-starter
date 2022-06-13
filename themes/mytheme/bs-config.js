module.exports = {
	ui: false,
	files: ["build", "**/*.{php,twig}"],
	ignore: ["node_modules"],
	proxy: "http://localhost:8888",
	ghostMode: false,
	open: false,
	notify: false,
};
