#!/usr/bin/env node

import path from "path";
import url from "url";
import { $ } from "zx";
import { readConfig, readWpContainerId } from "./wp-env.mjs";

const __filename = url.fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const environment = process.argv[2] || "development";

if (!["development", "tests"].includes(environment)) {
	throw new Error(`Unable to set "${environment}" for environment`);
}

const wpConfig = await readConfig();
const containerId = await readWpContainerId(wpConfig, environment);

const setupDirs = {
	local: path.join(__dirname, "_wp-setup"),
	container: "/var/www/html/.wp-setup",
};

// cleanup
await $`wp-env clean ${environment}`;

// preparation
await $`wp-env run cli "rm -rf ${setupDirs.container}"`;
await $`docker cp ${setupDirs.local} "${containerId}:${setupDirs.container}"`;

// execution
const service = environment === "development" ? "cli" : "tests-cli";
await $`docker-compose --file ${wpConfig.dockerComposeConfigPath} run -T ${service} php ${setupDirs.container}/setup.php`;
