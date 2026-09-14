# Agriwater Solutions

Agriwater Solutions is a Laravel web application focused on addressing water challenges in eastern Kenya and supporting sustainable agriculture in the region.

## Project Aim and Impact

The project aims to make water resources more accessible, reliable, and useful for communities and farmers. It seeks to:

- Tackle limited and unreliable access to water.
- Support farmers with practical agricultural water solutions.
- Improve agricultural productivity and strengthen food security.
- Encourage efficient and sustainable use of available water resources.
- Help communities become more resilient to water shortages and changing climate conditions.

The intended impact is healthier communities, more productive farms, and stronger local resilience through better water management and agricultural support.

## Technology Stack

- PHP 8.3+
- Laravel 13
- SQLite for local development
- Blade views
- Vite
- Tailwind CSS 4
- PHPUnit

## Project Structure

```text
app/                 Application classes, controllers, and models
bootstrap/            Framework bootstrap files
config/               Application configuration
database/             Migrations, factories, and seeders
public/               Web entry point and public assets
resources/css/        Tailwind entry stylesheet
resources/js/         Frontend entry point
resources/views/      Blade templates
routes/               Web and console routes
tests/                Feature and unit tests
```

The `public/` directory currently contains:

- `index.php` - Laravel's web entry point.
- `.htaccess` - Apache rewrite configuration.
- `robots.txt` - Crawler instructions.
- `favicon.ico` - Site favicon placeholder.

## Local Setup

### Requirements

- PHP 8.3 or later
- Composer
- Node.js and npm
- SQLite

### Installation

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

On macOS or Linux, use `cp .env.example .env` instead of the Windows `copy` command.

### Development

Start the Laravel development server and Vite together with:

```bash
composer run dev
```

The application is then available at `http://localhost:8000`.

To run the frontend bundler separately:

```bash
npm run dev
```

## Testing

Run the test suite with:

```bash
php artisan test
```

## Useful Commands

```bash
php artisan route:list
php artisan migrate
php artisan migrate:fresh --seed
vendor/bin/pint
```

## Laravel Documentation

- [Laravel documentation](https://laravel.com/docs)
- [Blade documentation](https://laravel.com/docs/blade)
- [Vite documentation](https://laravel.com/docs/vite)
- [Tailwind CSS documentation](https://tailwindcss.com/docs)
