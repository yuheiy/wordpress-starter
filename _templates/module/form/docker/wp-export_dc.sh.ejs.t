---
to: scripts/wp-export_dc.sh
---

#!/bin/bash

set -eu
set -o pipefail

SQL_FILE="wordpress.sql"

script_dir="$(cd $(dirname "${BASH_SOURCE[0]}"); pwd)"
snapshot_dir="$script_dir/snapshot"
container_id="$(docker ps -f name=local_wp_container -q)"

rm -rf "$snapshot_dir"
mkdir "$snapshot_dir"

mysqldump --host 0.0.0.0 --port 3306 -u wordpress -p --no-tablespaces wordpress > "$snapshot_dir/wordpress.sql"
docker cp "$container_id:/var/www/html/wp-content/uploads" "$snapshot_dir/."