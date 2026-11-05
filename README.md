<p align="center">
  <img src="https://i.postimg.cc/QNgXP3y1/Twitter-header-1-%282%29.png" alt="Bibliotheca" width="100%">
</p>

# Bibliotheca

A Library Management System built with Laravel. It handles book records, borrowing, returns, and a dashboard with live stats and charts.

## Tech Stack

- PHP / Laravel (Blade templating)
- Bootstrap 5
- Chart.js
- MySQL-compatible database (TiDB Cloud)

## Setup

Run these inside `bibliotheca-laravel`:

```bash
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
```

Then set your database details in `.env` and run:

```bash
php artisan migrate
php artisan serve
```

**Requirements:** PHP 8.2+, Composer, Node.js, Git, and MySQL (XAMPP) or a TiDB Cloud connection.
