#!/bin/bash

set -e

if [ -n "$CI" ]; then
    exit 0
fi

cd "$(dirname "$(dirname "${BASH_SOURCE[0]}")")"

plugin_dir="plugins"
acf_plugin_dir="$plugin_dir/advanced-custom-fields-pro"
acf_zip_file="advanced-custom-fields-pro.zip"

if [ -e "$acf_plugin_dir" ]; then
    echo "Advanced Custom Fields PRO is already installed"
    exit 0
fi

# shellcheck disable=SC1091
source .env

curl "https://connect.advancedcustomfields.com/v2/plugins/download?p=pro&k=$ACF_KEY" >"$acf_zip_file"
unzip "$acf_zip_file" -d "$plugin_dir"
rm "$acf_zip_file"
