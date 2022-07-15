const glob = require("glob");
const { container, kerning } = require("./tailwind-plugins");

// https://github.com/WebDevStudios/wd_s/pull/804#issuecomment-997018146
const topLevelPhpFiles = glob.sync("./*.php");

/** @type {import('tailwindcss/types').Config} */
module.exports = {
	content: [
		...topLevelPhpFiles,
		"./inc/**/*.php",
		"./views/**/*.twig",
		"./src/**/*.{js,ts}",
		"./languages/*.po",
	],
	future: {
		hoverOnlyWhenSupported: true,
	},
	theme: {
		extend: {
			fontFamily: {
				sans: ["sans-serif"],
				serif: ["serif"],
			},
		},
	},
	corePlugins: {
		container: false,
	},
	plugins: [container, kerning],
};
