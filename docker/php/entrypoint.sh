#!/bin/sh
set -e
cd /var/www/html

[ -f .env ] || cp .env.example .env

if [ ! -d vendor ] || [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

if ! grep -q '^APP_KEY=base64' .env; then
    php artisan key:generate --force
fi

if [ ! -f public/build/manifest.json ]; then
    npm install
    npm run build
fi

echo "Waiting for database..."
until php -r 'new PDO("mysql:host=".getenv("DB_HOST").";port=".getenv("DB_PORT"), getenv("DB_USERNAME"), getenv("DB_PASSWORD"));' 2>/dev/null; do
    sleep 2
done

php artisan migrate --force
php artisan storage:link 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
