import path from "path";
import url from "url";
import { $ } from "zx";
import { readConfig as _readConfig } from "@wordpress/env/lib/config/index.js";

const __filename = url.fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const rootDir = path.join(__dirname, "..");
const configPath = path.join(rootDir, ".wp-env.json");

export function readConfig() {
	return Promise.resolve(_readConfig(configPath));
}

export function readWpContainerId(
	{ dockerComposeConfigPath },
	environment = "development"
) {
	const service =
		environment === "development" ? "wordpress" : "tests-wordpress";
	return $`docker-compose --file "${dockerComposeConfigPath}" ps -q "${service}"`;
}
