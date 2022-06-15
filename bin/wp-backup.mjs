#!/usr/bin/env node

import "zx/globals";
import { packageDirectory } from "pkg-dir";
import { wpCliServices, readConfig, readWpContainerId } from "./wp-env.mjs";

const wpConfig = await readConfig();
const projectDir = await packageDirectory();
const remoteConfig = await fs
	.readJson(path.join(projectDir, "wp-remote-config.json"))
	.catch(() => {});

let [command, environmentType, environmentName] = argv._;

if (!["save", "restore"].includes(command)) {
	throw new Error(`"${command}" is not a valid command`);
}

if (!environmentType) {
	environmentType = "local";
	environmentName = "development";
}

if (!["local", "remote"].includes(environmentType)) {
	throw new Error(`"${environmentType}" is not a valid environment type`);
}

const isLocalMode = environmentType === "local";
const isRemoteMode = environmentType === "remote";

if (
	!(
		(isLocalMode && Object.keys(wpConfig.env).includes(environmentName)) ||
		(isRemoteMode &&
			typeof remoteConfig === "object" &&
			Object.keys(remoteConfig).includes(environmentName))
	)
) {
	throw new Error(`"${environmentName}" is not a valid environment name for "${environmentType}"`);
}

const backupDir = path.join(projectDir, ".wp-backup", environmentType, environmentName);

if (isLocalMode) {
	const wpContainerId = await readWpContainerId(wpConfig, environmentName);
	const wpCliService = wpCliServices[environmentName];

	const dbPaths = {
		container: "/var/www/html/.wordpress.sql",
		local: path.join(backupDir, "wordpress.sql"),
	};

	const uploadsDirs = {
		container: "/var/www/html/wp-content/uploads",
		local: path.join(backupDir, "uploads"),
	};

	if (command === "save") {
		// cleanup
		await $`rm -rf ${backupDir}`;
		await $`mkdir -p ${backupDir}`;

		// save db
		await $`npx wp-env run ${wpCliService} "wp db export ${dbPaths.container}"`;
		await $`docker cp "${wpContainerId}:${dbPaths.container}" ${dbPaths.local}`;
		await $`npx wp-env run ${wpCliService} "rm -f ${dbPaths.container}"`;

		// save uploads
		await $`docker cp "${wpContainerId}:${uploadsDirs.container}" ${uploadsDirs.local}`;
	}

	if (command === "restore") {
		// restore db
		await $`docker cp ${dbPaths.local} "${wpContainerId}:${dbPaths.container}"`;
		await $`npx wp-env run ${wpCliService} "wp db import ${dbPaths.container}"`;
		await $`npx wp-env run ${wpCliService} "rm -f ${dbPaths.container}"`;

		// restore uploads
		await $`npx wp-env run ${wpCliService} "rm -rf ${uploadsDirs.container}"`;
		await $`docker cp ${uploadsDirs.local} "${wpContainerId}:${uploadsDirs.container}"`;
	}
}

if (isRemoteMode) {
	const { server, ssh } = remoteConfig[environmentName];

	const dbPaths = {
		local: path.join(backupDir, "wordpress.sql"),
	};

	const uploadsDirs = {
		local: path.join(backupDir, "uploads"),
		remote: path.join(server.path, "wp-content", "uploads"),
	};

	if (command === "save") {
		// cleanup
		await $`rm -rf ${backupDir}`;
		await $`mkdir -p ${backupDir}`;

		// save db
		await $`wp --ssh=${ssh.user}@${ssh.host}:${server.path} db export - > ${dbPaths.local}`;

		// save uploads
		await $`rsync -e ssh -avz --delete ${ssh.user}@${ssh.host}:${uploadsDirs.remote}/ ${uploadsDirs.local}/`;
	}

	if (command === "restore") {
		// restore db
		await $`wp --ssh=${ssh.user}@${ssh.host}:${server.path} db import - < ${dbPaths.local}`;

		// restore uploads
		await $`rsync -e ssh -avz --delete ${uploadsDirs.local}/ ${ssh.user}@${ssh.host}:${uploadsDirs.remote}/`;
	}
}
