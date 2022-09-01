#!/usr/bin/env node

/**
 * Usage:
 * $ bin/wp-sync.mjs push staging --db --uploads
 * $ bin/wp-sync.mjs pull production --db --uploads
 */

import "zx/globals";
import { packageDirectory } from "pkg-dir";
import { wpCliServices, readConfig, readWpContainerId } from "./wp-env.mjs";

const wpConfig = await readConfig();
const projectDir = await packageDirectory();
const remoteConfig = await fs.readJson(path.join(projectDir, "wp-remote-config.json"));

let [command, environmentName, remoteName] = argv._;

if (!["push", "pull"].includes(command)) {
	throw new Error(`"${command}" is not a valid command`);
}

if (argv._.length === 2) {
	[environmentName, remoteName] = ["development", environmentName];
}

if (!Object.keys(wpConfig.env).includes(environmentName)) {
	throw new Error(`"${environmentName}" is not a valid environment name`);
}

if (!Object.keys(remoteConfig).includes(remoteName)) {
	throw new Error(`"${remoteName}" is not a valid remote name`);
}

const wpContainerId = await readWpContainerId(wpConfig, environmentName);
const wpCliService = wpCliServices[environmentName];
const { server, ssh } = remoteConfig[remoteName];
const localPort = wpConfig.env[environmentName].port;

const tmpDir = await fs.mkdtemp(os.tmpdir());

const dbPaths = {
	container: "/var/www/html/.wordpress.sql",
	local: path.join(tmpDir, "wordpress.sql"),
};

const pluginsDirs = {
	local: path.join(projectDir, "source", "wp-content", "plugins"),
	remote: path.join(server.path, "wp-content", "plugins"),
};

const uploadsDirs = {
	container: "/var/www/html/wp-content/uploads",
	local: path.join(tmpDir, "uploads"),
	remote: path.join(server.path, "wp-content", "uploads"),
};

const wpConfigPaths = {
	local: path.join(projectDir, "wp-config.php"),
	remote: path.join(server.path, "wp-config.php"),
};

if (command === "push") {
	const tasks = [];

	if (argv.db) {
		tasks.push(pushDb());
	}

	if (argv.plugins) {
		tasks.push(pushPlugins());
	}

	if (argv.uploads) {
		taskt.push(pushUploads());
	}

	if (argv.config) {
		tasks.push(pushConfig());
	}

	await Promise.all(tasks);
}

if (command === "pull") {
	const tasks = [];

	if (argv.db) {
		tasks.push(pullDb());
	}

	if (argv.plugins) {
		tasks.uploads(pullPlugins());
	}

	if (argv.uploads) {
		tasks.push(pullUploads());
	}

	if (argv.config) {
		tasks.push(pullConfig());
	}

	await Promise.all(tasks);
}

await $`rm -rf ${tmpDir}`;

async function pushDb() {
	const containerBackupPath = "/var/www/html/.backup.sql";

	await $`npx wp-env run ${wpCliService} "wp db export ${containerBackupPath}"`;

	await $`npx wp-env run ${wpCliService} "wp search-replace \"http://localhost:${localPort}\" \"${server.scheme}://${server.host}\""`;
	await $`npx wp-env run ${wpCliService} "wp search-replace \"localhost:${localPort}\" \"${server.host}\""`;

	await $`npx wp-env run ${wpCliService} "wp db export ${dbPaths.container}"`;
	await $`docker cp "${wpContainerId}:${dbPaths.container}" ${dbPaths.local}`;
	await $`npx wp-env run ${wpCliService} "rm -f ${dbPaths.container}"`;

	await $`wp --ssh=${ssh.user}@${ssh.host}:${server.path} db import - < ${dbPaths.local}`;

	await $`npx wp-env run ${wpCliService} "wp db import ${containerBackupPath}"`;
	await $`npx wp-env run ${wpCliService} "rm -f ${containerBackupPath}"`;
}

async function pullDb() {
	await $`wp --ssh=${ssh.user}@${ssh.host}:${server.path} db export - > ${dbPaths.local}`;

	await $`docker cp ${dbPaths.local} "${wpContainerId}:${dbPaths.container}"`;
	await $`npx wp-env run ${wpCliService} "wp db import ${dbPaths.container}"`;
	await $`npx wp-env run ${wpCliService} "rm -f ${dbPaths.container}"`;

	await $`npx wp-env run ${wpCliService} "wp search-replace \"${server.scheme}://${server.host}\" \"http://localhost:${localPort}\""`;
	await $`npx wp-env run ${wpCliService} "wp search-replace \"${server.host}\" \"localhost:${localPort}\""`;
}

async function pushPlugins() {
	await $`rsync -e ssh -avz --delete ${pluginsDirs.local}/ ${ssh.user}@${ssh.host}:${pluginsDirs.remote}/`;
}

async function pullPlugins() {
	await $`rsync -e ssh -avz --delete ${ssh.user}@${ssh.host}:${pluginsDirs.remote}/ ${pluginsDirs.local}/`;
}

async function pushUploads() {
	await $`docker cp "${wpContainerId}:${uploadsDirs.container}" ${uploadsDirs.local}`;

	await $`rsync -e ssh -avz --delete ${uploadsDirs.local}/ ${ssh.user}@${ssh.host}:${uploadsDirs.remote}/`;
}

async function pullUploads() {
	await $`rsync -e ssh -avz --delete ${ssh.user}@${ssh.host}:${uploadsDirs.remote}/ ${uploadsDirs.local}/`;

	await $`npx wp-env run ${wpCliService} "rm -rf ${uploadsDirs.container}"`;
	await $`docker cp ${uploadsDirs.local} "${wpContainerId}:${uploadsDirs.container}"`;
}

async function pushConfig() {
	await $`rsync -e ssh -avz --delete ${wpConfigPaths.local} ${ssh.user}@${ssh.host}:${wpConfigPaths.remote}`;
}

async function pullConfig() {
	await $`rsync -e ssh -avz --delete ${ssh.user}@${ssh.host}:${wpConfigPaths.remote} ${wpConfigPaths.local}`;
}
