# Silsilah Financial Technology Sciences — corporate site

Laravel 13 · Livewire 3 · Filament 4 · Tailwind v4 · spatie translatable / medialibrary / sitemap.

## Run
    composer install && npm install && npm run build
    cp .env.example .env && php artisan key:generate
    php artisan migrate --seed && php artisan storage:link
    php artisan serve            # site: /ar  /en   admin: /admin
    php artisan queue:work       # contact-form mail

Seeded admin: `admin@silsilah.example` / `password` (override with ADMIN_EMAIL / ADMIN_PASSWORD before seeding; change in production).

MySQL: set `DB_CONNECTION=mysql` + credentials in `.env`, then migrate. Dev default is SQLite.
Optional Turnstile: set `TURNSTILE_SITE_KEY` / `TURNSTILE_SECRET_KEY`. Notification address: Settings page email, else `MAIL_ADMIN_ADDRESS`.

Admin UI strings: `lang/{ar,en}/filament.php`. Public strings: `lang/{ar,en}/site.php`. Admin language toggle is in the user menu.
Placeholder content is marked `TODO: confirm with client`.
