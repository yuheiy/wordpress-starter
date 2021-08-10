#!/bin/bash

set -e

if [ -n "$CI" ]; then
  exit 0
fi

root_dir="$(dirname "$(cd "$(dirname "${BASH_SOURCE[0]}")" >/dev/null && pwd)")"
plugin_dir="$root_dir/plugins"
acf_plugin_dir="$plugin_dir/advanced-custom-fields-pro"

if [ -e "$acf_plugin_dir" ]; then
	echo "Advanced Custom Fields PRO is already installed"
	exit 0
fi

source "$root_dir/.env"

acf_zip_file="$root_dir/advanced-custom-fields-pro.zip"

rm -rf "$acf_plugin_dir"
curl "https://connect.advancedcustomfields.com/v2/plugins/download?p=pro&k=$ACF_KEY" > "$acf_zip_file"
unzip "$acf_zip_file" -d "$plugin_dir"
rm "$acf_zip_file"
