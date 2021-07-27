#!/bin/bash

set -e

script_dir="$(cd $(dirname "${BASH_SOURCE[0]}"); pwd)"
fixtures_dir="$script_dir/fixtures"
container_id="$(docker ps -f name=_wordpress_ -q)"

# language
wp-env run cli "wp language core install ja"
wp-env run cli "wp site switch-language ja"

# theme
wp-env run cli "wp theme activate mytheme"

# options
wp-env run cli "wp option update blogname \"The Boilerplate for WordPress\""
wp-env run cli "wp option update permalink_structure \"/%postname%/\""
wp-env run cli "wp option update timezone_string \"Asia/Tokyo\""

# posts
wp-env run cli "wp post update 3 --post_title=\"プライバシーポリシー\" --post_status=publish"

docker cp "$fixtures_dir/" "$container_id:/var/www/html/fixtures/"

wp-env run cli "wp post create fixtures/post-content.txt --post_title=\"投稿 1\" --post_status=publish"
wp-env run cli "wp post create fixtures/post-content.txt --post_title=\"投稿 2\" --post_status=publish"
wp-env run cli "wp post create fixtures/post-content.txt --post_title=\"投稿 3\" --post_status=publish"

wp-env run cli "wp post create fixtures/post-content-feature.txt --post_title=\"特集 1\" --post_status=publish --post_type=feature"
wp-env run cli "wp post create fixtures/post-content-feature.txt --post_title=\"特集 2\" --post_status=publish --post_type=feature"
wp-env run cli "wp post create fixtures/post-content-feature.txt --post_title=\"特集 3\" --post_status=publish --post_type=feature"

# menu
wp-env run cli "wp menu create head"
wp-env run cli "wp menu location assign head page-head-menu"
# https://github.com/wp-cli/entity-command/issues/214
# wp menu item add-archive ...

wp-env run cli "wp menu create foot"
wp-env run cli "wp menu location assign foot page-foot-menu"
# https://github.com/wp-cli/entity-command/issues/214
# wp menu item add-archive ...
wp-env run cli "wp menu item add-post foot 3"
