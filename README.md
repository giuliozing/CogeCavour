# CogeCavour

CogeCavour was a web platform built for the "cogestione" (student-led co-management) week
at Liceo Cavour, a week in March 2021 where students organize and run their own courses and
activities in place of regular classes. The platform let students browse and sign up for
courses, manage class rosters, share notes, and run petitions, with teachers and class
representatives able to create and edit course schedules.

This is a student/hobby project built for a one-off school event. It is no longer
maintained or deployed, and is published here as an archived record of the work.

## Stack

- **Backend:** PHP / [Laravel 8](https://laravel.com) REST API, authentication via
  [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- **Frontend:** [React](https://react.dev) with [Ant Design](https://ant.design)
  (only the production build output is included under `public/`; the frontend source
  lived in a separate project that was not preserved)
- **Database:** MySQL

## Main features

- Course catalog and sign-up ("cogestione" course plans, browsing by class)
- Notes sharing between students (`appunti`)
- Petitions
- JWT-based authentication and registration

## Project structure

This is a fairly standard Laravel application:

- `app/Http/Controllers` – API and web controllers
- `app/Models` – Eloquent models
- `routes/api.php`, `routes/web.php` – route definitions
- `database/migrations` – database schema
- `resources/views` – Blade views for the few server-rendered pages (login, home)
- `public/` – compiled frontend assets served by the Laravel app

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan serve
```

`.env.example` only contains placeholder values; never commit a real `.env` file.

## Known limitations

This was written in a few days by a high school student for a single one-week event, not
production software. If you're reading the code for reference, keep in mind:

- Passwords are hashed with unsalted SHA-256 instead of bcrypt/Argon2 in some legacy
  registration/login paths (`AuthController`); the main API auth flow uses Laravel's
  default hashing.
- CORS is wide open (`config/cors.php` allows all origins) and the custom `Cors`
  middleware sets a non-standard header.
- There is no automated test coverage beyond the default Laravel scaffolding.

## License

No license is specified; this repository is shared for reference purposes only.
