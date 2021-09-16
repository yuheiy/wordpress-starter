---
to: scripts/wp-import_dc.sh
---
#!/bin/bash

set -eu

root_dir="$(dirname "$(cd "$(dirname "${BASH_SOURCE[0]}")" >/dev/null && pwd)")"
container_id="$(docker ps -f name=local_wp_container -q)"

mysql --host 0.0.0.0 --port 3306 -u wordpress -p wordpress < scripts/snapshot/wordpress.sql

docker cp "$root_dir/scripts/snapshot/uploads" "$container_id:/var/www/html/wp-content/."
