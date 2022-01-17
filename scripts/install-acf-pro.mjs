#!/usr/bin/env node

import fs from "fs";
import path from "path";
import url from "url";
import { $ } from "zx";
import { readConfig } from "@wordpress/env/lib/config/index.js";

if (process.env.CI) {
	process.exit(0);
}

const __filename = url.fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const rootDir = path.join(__dirname, "..");
const pluginDir = path.join(rootDir, "plugins");
const acfDir = path.join(pluginDir, "advanced-custom-fields-pro");
const acfZipFile = path.join(rootDir, "advanced-custom-fields-pro.zip");

if (fs.existsSync(acfDir)) {
	console.log("Advanced Custom Fields PRO is already installed");
	process.exit(0);
}

const configPath = path.join(__dirname, "..", ".wp-env.json");
const { ACF_PRO_LICENSE } = (await readConfig(configPath)).env.development
	.config;

await $`curl "https://connect.advancedcustomfields.com/v2/plugins/download?p=pro&k=${ACF_PRO_LICENSE}" >"${acfZipFile}"`;
await $`unzip "${acfZipFile}" -d "${pluginDir}"`;
await $`rm "${acfZipFile}"`;
