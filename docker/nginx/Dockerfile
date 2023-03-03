FROM nginx:1.17-alpine

ARG APP_ENV=${APP_ENV:-prod}

COPY docker/nginx/conf.d/${APP_ENV}.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx/redirect.js /etc/nginx/redirect.js
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

COPY ./public /var/www/public

WORKDIR /var/www/
