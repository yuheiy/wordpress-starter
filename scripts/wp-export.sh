#!/bin/bash

set -eu

SQL_FILE="wordpress.sql"

script_dir="$(cd $(dirname "${BASH_SOURCE[0]}"); pwd)"
container_id="$(docker ps -f name=_wordpress_ -q)"

rm -r "$script_dir/snapshot"
mkdir "$script_dir/snapshot"

wp-env run cli "rm -f $SQL_FILE"
wp-env run cli "wp db export $SQL_FILE"
docker cp "$container_id:/var/www/html/$SQL_FILE" "$script_dir/snapshot/."

docker cp "$container_id:/var/www/html/wp-content/uploads" "$script_dir/snapshot/."
