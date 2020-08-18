#!/bin/bash

set -eu

SQL_FILE="wordpress.sql"

script_dir="$(cd $(dirname "${BASH_SOURCE[0]}"); pwd)"
container_id="$(docker ps -f name=_wordpress_ -q)"

docker cp "$script_dir/fixtures/$SQL_FILE" "$container_id:/var/www/html/$SQL_FILE"
wp-env run cli "wp db import $SQL_FILE"

wp-env run cli "wp language core install ja"

wp-env run cli "rm -rf wp-content/uploads"
docker cp "$script_dir/fixtures/uploads" "$container_id:/var/www/html/wp-content/."
