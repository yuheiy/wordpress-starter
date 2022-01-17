#!/usr/bin/env node

import path from "path";
import url from "url";
import { $ } from "zx";

const __filename = url.fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const snapshotDir = path.join(__dirname, "snapshot");
const containerId = await $`docker ps -f name=_wordpress_ -q`;

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
