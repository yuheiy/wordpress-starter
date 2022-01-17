#!/usr/bin/env node

import path from "path";
import url from "url";
import { $ } from "zx";
import { readConfig } from "@wordpress/env/lib/config/index.js";

const __filename = url.fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const environment = process.argv[2] || "development";

if (!["development", "tests"].includes(environment)) {
	throw new Error(`Unable to set "${environment}" for environment`);
}

const configPath = path.join(__dirname, "..", ".wp-env.json");
const { dockerComposeConfigPath } = await readConfig(configPath);
const containerId =
	await $`docker-compose --file ${dockerComposeConfigPath} ps -q ${
		environment === "development" ? "wordpress" : "tests-wordpress"
	}`;

const setupDirs = {
	local: path.join(__dirname, "wp-setup"),
	container: "/var/www/html/.wp-setup",
};

// cleanup
await $`wp-env clean ${environment}`;

// preparation
await $`rm -rf ${setupDirs.container}`;
await $`docker cp ${setupDirs.local} "${containerId}:${setupDirs.container}"`;

// execution
const service = environment === "development" ? "cli" : "tests-cli";
await $`docker-compose --file ${dockerComposeConfigPath} run -T ${service} php ${setupDirs.container}/setup.php`;
