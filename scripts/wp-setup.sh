#!/bin/bash

set -e

cd "$(dirname "$(dirname "${BASH_SOURCE[0]}")")"

if [ "$1" ]; then
  environment="$1"
else
  environment="development"
fi

case "$environment" in
"development" | "tests") ;;
*)
  echo "Unable to set \"$environment\" for environment" >&2
  exit 1
  ;;
esac

case "$environment" in
"development")
  container_id="$(docker ps -f name=_wordpress_ -q)"
  ;;
"tests")
  container_id="$(docker ps -f name=_tests-wordpress_ -q)"
  ;;
esac

case "$environment" in
"development")
  cli_command="cli"
  ;;
"tests")
  cli_command="tests-cli"
  ;;
esac

# cleanup
wp-env clean "$environment"

# preparation
docker cp "$(pwd)/scripts/_wp-setup/" "$container_id:/var/www/html/"

# execution
wp-env run "$cli_command" bash _wp-setup/main.sh
