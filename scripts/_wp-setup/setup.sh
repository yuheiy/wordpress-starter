#!/bin/bash

set -e

cd $(dirname "${BASH_SOURCE[0]}")

# language
wp language core install ja
wp site switch-language ja

# theme
wp theme activate mytheme

# options
wp option update blogname "The Boilerplate for WordPress"
wp option update timezone_string "Asia/Tokyo"

wp rewrite structure "/%postname%/"

# terms
wp term create category ニュース --slug=news
wp term create category サービス --slug=service
wp term create category 実績 --slug=work

wp term create post_tag Foo
wp term create post_tag Bar
wp term create post_tag Baz

# posts
wp post update "$(wp post list --title="Privacy Policy" --post_type=page --format=ids)" --post_title="プライバシーポリシー" --post_status=publish

wp post create fixtures/post-content.txt --post_title="投稿 1" --post_status=publish --post_category="$(wp term list category --slug=news --format=ids)"
wp post create fixtures/post-content.txt --post_title="投稿 2" --post_status=publish --post_category="$(wp term list category --slug=service --format=ids)"
wp post create fixtures/post-content.txt --post_title="投稿 3" --post_status=publish --post_category="$(wp term list category --slug=work --format=ids)"

wp post create fixtures/post-content-feature.txt --post_title="特集 1" --post_status=publish --post_type=mytheme_feature
wp post create fixtures/post-content-feature.txt --post_title="特集 2" --post_status=publish --post_type=mytheme_feature
wp post create fixtures/post-content-feature.txt --post_title="特集 3" --post_status=publish --post_type=mytheme_feature

# menu
wp menu create head
wp menu location assign head page-head-menu
# https://github.com/wp-cli/entity-command/issues/214
# wp menu item add-archive ...

wp menu create foot
wp menu location assign foot page-foot-menu
# https://github.com/wp-cli/entity-command/issues/214
# wp menu item add-archive ...
wp menu item add-post foot "$(wp post list --title="プライバシーポリシー" --post_type=page --format=ids)"
