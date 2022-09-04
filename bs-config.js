module.exports = {
	ui: false,
	files: [
		"source/wp-content/themes/mytheme/build",
		"source/wp-content/themes/mytheme/**/*.{php,twig}",
	],
	proxy: "http://localhost:8888",
	ghostMode: false,
	open: false,
	notify: false,
};
