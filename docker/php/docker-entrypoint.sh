#!/bin/sh
set -e

if [ "$1" = 'php-fpm' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then

	mkdir -p var/cache
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var

	if [ ! -d vendor ]; then
		composer install --prefer-dist --no-progress --no-interaction
	fi
fi

exec docker-php-entrypoint "$@"