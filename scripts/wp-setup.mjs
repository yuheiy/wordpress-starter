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
const { workDirectoryPath } = await readConfig(configPath);
const containerId =
	await $`docker-compose --project-directory ${workDirectoryPath} ps -q ${
		environment === "development" ? "wordpress" : "tests-wordpress"
	}`;

const cliCommand = environment === "development" ? "cli" : "tests-cli";

// cleanup
await $`wp-env clean ${environment}`;

// preparation
await $`rm -rf /var/www/html/_wp-setup/`;
await $`docker cp ${__dirname}/_wp-setup/ "${containerId}:/var/www/html/_wp-setup/"`;

// execution
await $`wp-env run ${cliCommand} bash /var/www/html/_wp-setup/main.sh`;
