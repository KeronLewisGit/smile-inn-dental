# Smile Inn Dental — deploying to Hostinger (`public_html`)

The whole project lives in `public_html`. The root `.htaccess` sends every request into `public/`
and blocks direct access to `.env`, `vendor/`, `storage/` and the rest. `public/build/` (CSS, JS and
fonts) is committed, so the server needs no Node.

## 1. hPanel

1. **PHP**: Websites → PHP configuration → **8.3** or **8.4** with `pdo_mysql`, `mbstring`, `fileinfo`, `gd` (all default).
2. **MySQL**: Databases → create a database and user; note the three values.
3. **Email**: create a mailbox such as `hello@smileinndental.com` and note its SMTP password. The site emails patients
   when they book, when you confirm/cancel, and the day before their visit; the clinic gets a copy of every booking and enquiry.
4. **SSL**: Security → SSL → active for the domain.

## 2. After `git clone` into `public_html` (SSH)

```sh
cd ~/public_html
php /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction
cp .env.hostinger.example .env
php artisan key:generate --force
nano .env                       # APP_URL, DB_*, MAIL_* values
php artisan migrate --force
php artisan db:seed --class=ContentSeeder --force   # services, team, booking types, testimonials, journal posts
php artisan clinic:admin                            # your admin login (prompts for a password)
chmod -R ug+rwx storage bootstrap/cache public/uploads
php artisan optimize
```

Then add one cron job in hPanel → Advanced → Cron Jobs (every minute):

```
* * * * * cd ~/public_html && php artisan schedule:run >> /dev/null 2>&1
```

It emails appointment reminders every day at 4 PM for the next day's confirmed bookings.

If `composer` is not on the path, use `php /usr/local/bin/composer`, or upload `composer.phar` and run `php composer.phar install --no-dev`.

Photos uploaded in Admin (team, services, journal covers) are stored in `public/uploads/`, which is gitignored.
Back that folder up along with the database.

## 3. Check

- `/` loads with styles and fonts. `/.env` returns 403. `/up` returns OK.
- `/admin/login` signs you in. Admin → Hours & booking: confirm the opening hours and the notification email.
- Open `/book` in a private window and request an appointment; it appears on the admin dashboard and both emails arrive.
- Confirm it from the dashboard; the patient gets the confirmation email.

## Updating later

```sh
cd ~/public_html && git pull && php /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction && php artisan migrate --force && php artisan optimize:clear && php artisan optimize
```
