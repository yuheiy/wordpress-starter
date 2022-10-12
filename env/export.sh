#!/bin/bash

set -e

root=$(dirname "$(wp config path)")

wp export --dir="${root}/env" --filename_format="data.xml"

rm -rf "${root}/env/uploads/"
cp -r "${root}/wp-content/uploads/." "${root}/env/uploads/"
