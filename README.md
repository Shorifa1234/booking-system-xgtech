# XGTECH Booking System: Landing Page

Landing page for the XGTECH online booking system, built in plain PHP with a MySQL/MariaDB database for demo requests.

- `index.php`: main "around the clock" design (scroll through a business day, live booking widget, demo form)
- `classic.php`: alternative classic design
- `submit.php`: validates the demo form and saves it to the database

## Requirements

- PHP 8.1+ with `pdo_mysql`
- MySQL or MariaDB

## Run locally (XAMPP)

1. Start Apache and MySQL in XAMPP.
2. Create the table: import `database/schema.sql` in phpMyAdmin, or run
   `mysql -u root < database/schema.sql`
3. Start the site:
   ```
   php -S 127.0.0.1:8099
   ```
   then open http://127.0.0.1:8099

## Configure

Edit `includes/config.php`:

- business name, email and phone
- database settings (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`)

On a live server, use a database user with a strong password, not `root`.

## Deploy

Upload all files to any PHP host (cPanel, Hostinger and similar), import `database/schema.sql`, and update the database settings in `includes/config.php`.

GitHub Pages cannot run PHP, so it cannot host this site.
