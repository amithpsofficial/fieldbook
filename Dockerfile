# ---- Stage 1: build front-end assets (Tailwind/Alpine via Vite) ----
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install --no-audit --no-fund
COPY vite.config.js postcss.config.js ./
COPY resources resources
COPY public public
RUN npm run build

# ---- Stage 2: PHP app served by nginx + php-fpm ----
FROM serversideup/php:8.4-fpm-nginx

ENV PHP_OPCACHE_ENABLE=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Automatically runs migrations, storage:link, and caches config on boot
ENV AUTORUN_ENABLED=true

USER root
COPY --chown=www-data:www-data . /var/www/html
COPY --from=assets --chown=www-data:www-data /app/public/build /var/www/html/public/build
USER www-data

RUN composer install --no-interaction --optimize-autoloader --no-dev