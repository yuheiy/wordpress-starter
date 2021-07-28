#!/bin/bash

set -e

if [ "$1" ]; then
	environment="$1"
else
	environment="development"
fi

case "$environment" in
	"development"|"tests")
		;;
	*)
		echo "Error: \$environment should be \"development\" or \"tests\""
		exit 1
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
script_dir="$(cd $(dirname "${BASH_SOURCE[0]}"); pwd)"
docker cp "$script_dir/_wp-setup/" "$container_id:/var/www/html/"

# execution
wp-env run "$cli_command" bash _wp-setup/setup.sh
