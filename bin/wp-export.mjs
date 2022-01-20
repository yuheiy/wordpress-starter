#!/usr/bin/env node

import path from "path";
import url from "url";
import { $ } from "zx";
import { readConfig, readWpContainerId } from "./wp-env.mjs";

const __filename = url.fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const snapshotDir = path.join(__dirname, "snapshot");

const wpConfig = await readConfig();
const containerId = await readWpContainerId(wpConfig);

// cleanup
await $`rm -rf ${snapshotDir}`;
await $`mkdir ${snapshotDir}`;

// export database
const dumpPaths = {
	local: path.join(snapshotDir, "wordpress.sql"),
	container: "/var/www/html/.wordpress.sql",
};

await $`wp-env run cli "wp db export ${dumpPaths.container}"`;
await $`docker cp "${containerId}:${dumpPaths.container}" ${dumpPaths.local}`;
await $`wp-env run cli "rm -f ${dumpPaths.container}"`;

// export uploads
const uploadsDirs = {
	local: path.join(snapshotDir, "uploads"),
	container: "/var/www/html/wp-content/uploads",
};

await $`docker cp "${containerId}:${uploadsDirs.container}" ${uploadsDirs.local}`;
