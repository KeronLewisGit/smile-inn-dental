# Smile Inn Dental

The website and booking backend for Smile Inn Dental, St. James, Trinidad. Laravel 13, Tailwind CSS 4, Alpine.js, SQLite locally and MySQL on Hostinger.

## What is in it

**Public site**: home, services (six service pages with every treatment listed), a dedicated Invisalign page with the iTero process, FAQs and a results gallery, emergency care, the clinic, the team with individual profiles, patient stories, a 38-post journal, contact form, newsletter sign-up and online booking.

**Online booking**: patients pick a booking type (general consult, Dr. Hazell consult, hygienist clean, free Invisalign consult, free virtual cosmetic consult, emergency, children's visit), a date from a live calendar and an open slot. Slots come from the clinic hours, the slot size and the number of chairs set in Admin. Patients get a confirmation email with a link to view or cancel; the clinic is emailed every request.

**Admin** (`/admin`): dashboard, week calendar, appointments (confirm, complete, no-show, cancel, reschedule, manual booking), patients, enquiries, newsletter subscribers with CSV export, and full editing of services, team, testimonials, journal posts and booking types. Admins also manage opening hours, booking rules and staff logins.

## Local setup

```sh
composer install && npm install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed          # content + demo bookings, admin login below
npm run build && php artisan serve --port=8150
```

| Login | Email | Password |
|---|---|---|
| Admin | admin@smileinn.test | password |

Run `php artisan test` for the feature suite. See `DEPLOY.md` for Hostinger.
