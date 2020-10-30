#!/bin/bash

set -eu

wp-env run cli "wp option update blogname \"The Boilerplate for WordPress\""
wp-env run cli "wp option update permalink_structure \"/%postname%/\""
wp-env run cli "wp option update timezone_string \"Asia/Tokyo\""

wp-env run cli "wp language core install ja"
wp-env run cli "wp site switch-language ja"

wp-env run cli "wp theme activate my-theme"

wp-env run cli "wp post create --post_type=page --post_status=publish --post_title=\"私たちについて\" --post_name=\"about\""
wp-env run cli "wp post update 3 --post_title=\"プライバシーポリシー\" --post_status=publish"

wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 1\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 2\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 3\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 4\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 5\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 6\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 7\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 8\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 9\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 10\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 11\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 12\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 13\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 14\""
wp-env run cli "wp post create --post_type=news --post_status=publish --post_title=\"ニュース 15\""

wp-env run cli "wp term create news_category \"メディア\" --slug=\"media\""
wp-env run cli "wp term create news_category \"イベント\" --slug=\"event\""
wp-env run cli "wp term create news_category \"プレスリリース\" --slug=\"press-release\""
