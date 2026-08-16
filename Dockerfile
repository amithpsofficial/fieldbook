# ---- Stage 1: build front-end assets (Tailwind/Alpine via Vite) ----
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js postcss.config.js ./
COPY resources resources
COPY public public
RUN npm run build

# ---- Stage 2: PHP app served by nginx + php-fpm ----
# This image auto-runs `composer install`, generates APP_KEY if missing,
# and runs `php artisan migrate --force` on every container boot.
FROM richarvey/nginx-php-fpm:php8.3

COPY . .
COPY --from=assets /app/public/build ./public/build

# --- Image runtime config ---
ENV SKIP_COMPOSER=0
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# --- Laravel config ---
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

CMD ["/start.sh"]
