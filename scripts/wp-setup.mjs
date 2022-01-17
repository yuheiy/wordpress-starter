#!/usr/bin/env node

import path from "path";
import url from "url";
import { $ } from "zx";

const __filename = url.fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const environment = process.argv[2] || "development";

if (!["development", "tests"].includes(environment)) {
	throw new Error(`Unable to set "${environment}" for environment`);
}

const container_id =
	environment === "development"
		? await $`docker ps -f name=_wordpress_ -q`
		: await $`docker ps -f name=_tests-wordpress_ -q`;

const cli_command = environment === "development" ? "cli" : "tests-cli";

// cleanup
await $`wp-env clean ${environment}`;

// preparation
await $`rm -rf /var/www/html/_wp-setup/`;
await $`docker cp ${__dirname}/_wp-setup/ "${container_id}:/var/www/html/_wp-setup/"`;

// execution
await $`wp-env run ${cli_command} bash /var/www/html/_wp-setup/main.sh`;
