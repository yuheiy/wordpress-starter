#!/bin/bash

set -eu

root_dir="$(dirname "$(cd "$(dirname "${BASH_SOURCE[0]}")" >/dev/null && pwd)")"

rm -rf "$root_dir/plugins/advanced-custom-fields-pro"
# プラグインファイルのURLを入力
curl "https://connect.advancedcustomfields.com/v2/plugins/download?p=pro&k=XXXXXXXX" > "$root_dir/advanced-custom-fields-pro.zip"
unzip "$root_dir/advanced-custom-fields-pro.zip" -d "$root_dir/plugins"
rm "$root_dir/advanced-custom-fields-pro.zip"
