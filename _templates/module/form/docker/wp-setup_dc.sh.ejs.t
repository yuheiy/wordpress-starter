---
to: scripts/wp-setup_dc.sh
---
#! /bin/bash

set -ue

container_id="$(docker ps -f name=local_wp_container -q)"

docker exec -it "$container_id" /tmp/wp-setup.sh