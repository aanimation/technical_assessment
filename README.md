## Overview
This project build using Laravel 9 include the database migrations and seeds, models as User, Address, Company, and Friend,
to provide quick show application based by progressing a PHP Coding Assignment of Jitera Hiring.

Follow and unfollow user handled by `users` and `friends` table relationships. then user more attributes like address and company stored in other as `companies` and `address` table.

- For test, deployed on http://jitera.puspitagroup.com
- Shared to `zrg-team`, `@justiniruuza`, `hr@jitera.com`
- Applicant `Arham Arifuddin` (github username as `aanimation`)
- Started `April 17th 2023`
- Deployed at `April 19th 2023`.

## Spesification
- Laravel v9.52.5 (PHP v8.2.0)
- Passport V.11 for API authentication

*Note: oauth keys included for quick test*

## Quick Start
1. Clone this git repo.
1. Run `composer install`
1. Copy .env.example to .env file
1. Setup database name and credentials
1. Run `php artisan migrate:refresh --seed`, already provided seed for users and friends
1. Run `php artisan passport:install`, use passport for API
1. Run the application, `php artisan serve --port=3000`

## APIs
1. `register`, input required : name, username, email, password, confirm_password
1. `login`, input required : email & password
1. `users`, auth token required
1. `follow`, auth token required, input require : follow_id
1. `unfollow`, auth token required, input require : unfollow_id
1. `followers`, auth token required, (alt) input to search follower name : search