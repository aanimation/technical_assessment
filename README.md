## Spesification
- Laravel v9.52.5 (PHP v8.2.0)
- Passport V.11 for API authentication

## Quick Start
1. Clone this git repo.
1. Run `composer install`
1. Copy `.env.example` to `.env` file
1. Setup database name and credentials on `.env` file
1. Run `php artisan migrate --seed`
1. Run `php artisan passport:install`
1. Run `php artisan serve --port=3000`

## APIs
1. `api/verify`, input required : `file_content` must json file and max. 2MB

## Note
1. Please use `application/json` for `Content-Type` and `Accept` headers