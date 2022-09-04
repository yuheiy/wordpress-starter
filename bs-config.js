module.exports = {
	ui: false,
	files: ["source/wp-content/themes/main/build", "source/wp-content/themes/main/**/*.{php,twig}"],
	ignore: ["node_modules"],
	proxy: "http://localhost:8888",
	ghostMode: false,
	open: false,
	notify: false,
};
