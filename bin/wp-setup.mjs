#!/usr/bin/env node

import "zx/globals";
import { wpCliServices, readConfig } from "./wp-env.mjs";

const wpConfig = await readConfig();

const environmentName = argv._[0] || "development";

if (!Object.keys(wpConfig.env).includes(environmentName)) {
	throw new Error(`"${environmentName}" is not a valid environment name`);
}

const wpCliService = wpCliServices[environmentName];
const uploadsDir = "/var/www/html/wp-content/uploads";
const setupPath = "/var/www/html/.wp-setup/setup.php";

await $`npx wp-env clean ${environmentName}`;
await $`npx wp-env run ${wpCliService} "rm -rf ${uploadsDir}/2022/"`; // fixme: glob not work
await $`docker-compose --file ${wpConfig.dockerComposeConfigPath} run -T ${wpCliService} php ${setupPath}`;
