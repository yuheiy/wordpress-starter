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

// import database
const dumpPaths = {
	local: path.join(snapshotDir, "wordpress.sql"),
	container: "/var/www/html/.wordpress.sql",
};

await $`docker cp ${dumpPaths.local} "${containerId}:${dumpPaths.container}"`;
await $`wp-env run cli "wp db import ${dumpPaths.container}"`;
await $`wp-env run cli "rm -f ${dumpPaths.container}"`;

// import uploads
const uploadsDirs = {
	local: path.join(snapshotDir, "uploads"),
	container: "/var/www/html/wp-content/uploads",
};

await $`wp-env run cli "rm -rf ${uploadsDirs.container}"`;
await $`docker cp ${uploadsDirs.local} "${containerId}:${uploadsDirs.container}"`;
