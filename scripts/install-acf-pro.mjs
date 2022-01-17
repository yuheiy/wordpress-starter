#!/usr/bin/env node

import fs from "fs";
import path from "path";
import url from "url";
import { $ } from "zx";

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

const { ACF_PRO_LICENSE } = JSON.parse(
	fs.readFileSync(path.join(rootDir, ".wp-env.override.json"))
).config;

await $`curl "https://connect.advancedcustomfields.com/v2/plugins/download?p=pro&k=${ACF_PRO_LICENSE}" >"${acfZipFile}"`;
await $`unzip "${acfZipFile}" -d "${pluginDir}"`;
await $`rm "${acfZipFile}"`;
