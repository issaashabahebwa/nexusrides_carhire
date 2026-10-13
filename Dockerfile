FROM php:8.2-cli

WORKDIR /app

# Install system + Node
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev sqlite3 libsqlite3-dev curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_sqlite zip mbstring

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN mkdir -p database && touch database/database.sqlite
RUN php artisan config:clear

EXPOSE 10000

CMD sh -c "php artisan migrate --force && php artisan storage:link || true && php artisan serve --host=0.0.0.0 --port=10000"