#!/usr/bin/env node

import path from "path";
import url from "url";
import { $ } from "zx";
import { readConfig } from "@wordpress/env/lib/config/index.js";

const __filename = url.fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const snapshotDir = path.join(__dirname, "snapshot");

const configPath = path.join(__dirname, "..", ".wp-env.json");
const { dockerComposeConfigPath } = await readConfig(configPath);
const containerId =
	await $`docker-compose --file ${dockerComposeConfigPath} ps -q wordpress`;

// import database
const dumpFilePaths = {
	local: path.join(snapshotDir, "wordpress.sql"),
	container: "/var/www/html/wordpress.sql",
};

await $`docker cp ${dumpFilePaths.local} "${containerId}:${dumpFilePaths.container}"`;
await $`wp-env run cli "wp db import ${dumpFilePaths.container}"`;

// import uploads
const uploadsDirs = {
	local: path.join(snapshotDir, "uploads"),
	container: "/var/www/html/wp-content/uploads",
};

await $`wp-env run cli "rm -rf ${uploadsDirs.container}"`;
await $`docker cp ${uploadsDirs.local} "${containerId}:${uploadsDirs.container}"`;
