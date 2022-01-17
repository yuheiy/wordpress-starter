#!/usr/bin/env node

import path from "path";
import url from "url";
import { $ } from "zx";

const __filename = url.fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const snapshotDir = path.join(__dirname, "snapshot");
const containerId = await $`docker ps -f name=_wordpress_ -q`;

// cleanup
await $`rm -rf ${snapshotDir}`;
await $`mkdir ${snapshotDir}`;

// export database
const dumpFilePaths = {
	local: path.join(snapshotDir, "wordpress.sql"),
	container: "/var/www/html/wordpress.sql",
};

await $`wp-env run cli "rm -f ${dumpFilePaths.container}"`;
await $`wp-env run cli "wp db export ${dumpFilePaths.container}"`;
await $`docker cp "${containerId}:${dumpFilePaths.container}" ${dumpFilePaths.local}`;

// export uploads
const uploadsDirs = {
	local: path.join(snapshotDir, "uploads"),
	container: "/var/www/html/wp-content/uploads",
};

await $`rm -rf ${uploadsDirs.local}`;
await $`docker cp "${containerId}:${uploadsDirs.container}" ${uploadsDirs.local}`;
