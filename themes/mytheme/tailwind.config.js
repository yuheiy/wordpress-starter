const glob = require("glob");

const topLevelPhpFiles = glob.sync("./*.php");

module.exports = {
	// https://github.com/WebDevStudios/wd_s/pull/804#issuecomment-997018146
	content: [...topLevelPhpFiles, "./inc/**/*.php", "./views/**/*.twig", "./src/**/*.{js,ts}"],
	theme: {
		extend: {},
	},
	plugins: [],
};
