1. Clone the Repository

Clone the project from GitHub and move into the project directory:

git clone https://github.com/Ezra-Ben/clothe-scm.git
cd scm-system

Replace the URL above with the actual GitHub repository link.

2. Install PHP Dependencies (Laravel + Livewire + Volt)

Install the required PHP packages using Composer:

composer install

This includes Laravel, Livewire, Volt, and other dependencies.

3. Set Up Environment File

Create your local .env file and generate the application key:
php artisan key:generate

Then edit your .env file and ensure the following database settings:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=scm
DB_USERNAME=root
DB_PASSWORD=

4. Create the MySQL Database

Open phpMyAdmin or your MySQL client and run:

CREATE DATABASE scm;

Make sure the database name matches what's in .env.

5. Run Migrations

Run Laravel migrations to set up the database tables:

php artisan migrate

6. Install Node.js and Frontend Dependencies

If Node.js is not installed, download it from:
https://nodejs.org

Then install frontend dependencies:

npm install
npm run dev

Leave npm run dev running in one terminal to serve Vite assets.

7. Start Laravel Development Server

In a new terminal window:

php artisan serve

Visit the app in your browser:

http://localhost:8000

You should see the Laravel welcome screen, with working login/register pages.