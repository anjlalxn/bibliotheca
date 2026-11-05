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

> **Note:** Node.js and `npm` are **not required** for now. Bootstrap 5 and Chart.js are loaded through CDN links in the Blade layout, so there is no `npm install` or `npm run build` step.
> The default Laravel starter files for Vite and Tailwind (`package.json`, `vite.config.js`, `resources/css`, `resources/js`) are still in the project and may be removed or used later. If the project switches to npm-based assets, this README will be updated.
>
> An internet connection is needed for pages to load their styles and charts.
