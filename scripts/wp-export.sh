#!/bin/bash

set -e

SQL_FILE="wordpress.sql"

script_dir="$(cd $(dirname "${BASH_SOURCE[0]}"); pwd)"
snapshot_dir="$script_dir/snapshot"
container_id="$(docker ps -f name=_wordpress_ -q)"

rm -rf "$snapshot_dir"
mkdir "$snapshot_dir"

wp-env run cli "rm -f $SQL_FILE"
wp-env run cli "wp db export $SQL_FILE"
docker cp "$container_id:/var/www/html/$SQL_FILE" "$snapshot_dir/."

docker cp "$container_id:/var/www/html/wp-content/uploads" "$snapshot_dir/."
