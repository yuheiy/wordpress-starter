import "zx/globals";
import { packageDirectory } from "pkg-dir";
import { readConfig as _readConfig } from "@wordpress/env/lib/config/index.js";

export const wpServices = {
	development: "wordpress",
	tests: "tests-wordpress",
};

export const wpCliServices = {
	development: "cli",
	tests: "tests-cli",
};

export async function readConfig() {
	const projectDir = await packageDirectory();
	const configPath = path.join(projectDir, ".wp-env.json");

	return _readConfig(configPath);
}

export function readWpContainerId({ dockerComposeConfigPath }, environmentName = "development") {
	const service = wpServices[environmentName];

	return $`docker-compose --file ${dockerComposeConfigPath} ps -q ${service}`;
}
