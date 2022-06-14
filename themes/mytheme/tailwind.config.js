const glob = require("glob");
const { container, fluidText, kerning } = require("./tailwind-plugins");

const topLevelPhpFiles = glob.sync("./*.php");

/** @type {import('tailwindcss/types').Config} */
module.exports = {
	// https://github.com/WebDevStudios/wd_s/pull/804#issuecomment-997018146
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
	plugins: [container, fluidText, kerning],
};
