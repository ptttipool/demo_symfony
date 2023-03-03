ARG PHP_VERSION=7.4.5

FROM php:${PHP_VERSION}-fpm-alpine

RUN apk add --no-cache \
		acl \
		fcgi

ARG APCU_VERSION=5.1.18

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		zlib-dev \
	; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
		intl \
		pdo_mysql \
		zip \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

COPY docker/php/conf.d/api-php.ini $PHP_INI_DIR/conf.d/api-php.ini
COPY docker/php/conf.d/php-memlimit.ini $PHP_INI_DIR/conf.d/php-memlimit.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV SYMFONY_PHPUNIT_VERSION=8.3

ENV COMPOSER_ALLOW_SUPERUSER=1

ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /var/www

ARG APP_ENV=prod

COPY composer.json composer.lock symfony.lock ./

RUN set -eux; \
	composer install --prefer-dist --no-dev --no-scripts --no-progress; \
	composer clear-cache

COPY .env ./
COPY bin bin/
COPY config config/
COPY public public/
COPY src src/
COPY translations translations/
COPY templates templates/

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync

ARG XDEBUG_VERSION=2.9.2

RUN set -eux; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
	pecl install xdebug-$XDEBUG_VERSION; \
	docker-php-ext-enable xdebug; \
	apk del .build-deps

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint

RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]

CMD ["php-fpm"]
