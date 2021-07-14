#!/bin/bash

set -e

wp-env run cli "wp option update blogname \"The Boilerplate for WordPress\""
wp-env run cli "wp option update permalink_structure \"/%postname%/\""
wp-env run cli "wp option update timezone_string \"Asia/Tokyo\""

wp-env run cli "wp language core install ja"
wp-env run cli "wp site switch-language ja"

wp-env run cli "wp theme activate theme"

wp-env run cli "wp post update 3 --post_title=\"プライバシーポリシー\" --post_status=publish"
