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

Then set your database details in `.env` and run php artisan commands:

```env
DB_CONNECTION=mysql
DB_HOST= PROVIDED CREDENTIALS HERE
DB_PORT=4000
DB_DATABASE=bibliotheca_DB
DB_USERNAME= PROVIDED CREDENTIALS HERE
DB_PASSWORD=PROVIDED CREDENTIALS HERE

```


```bash
php artisan migrate
php artisan serve
```

## Book Data Import (Do Not Run)

> **Please do not run these commands.** The `books` and `categories` tables were already populated. Running them again will add extra books and change the sample data we are using for testing.

The commands are kept in the project only to show how the sample data was created:

| Command | What it does |
|---|---|
| `php artisan books:import` | Imports general books (fiction, science, history, technology, etc.) |
| `php artisan books:import-filipino` | Imports Filipino-related books (literature, history, folklore, culture, etc.) |
| `php artisan books:import-more` | Imports more general and educational books (psychology, art, programming, comics, IT, nursing, education, engineering) |

Files are located in `app/Console/Commands/`.

### How we did it

1. **Source:** We used the free [Open Library Search API](https://openlibrary.org/dev/docs/api/search) to get real book titles, authors, publishers, and publication years.
2. **Artisan commands:** Each command is a custom Laravel Artisan command that searches Open Library by subject (for example, `subject:psychology`).
3. **Categories:** Each subject is saved as a row in the `categories` table, using `firstOrCreate` so categories are not duplicated.
4. **Books:** Each result is saved to the `books` table under its category. Missing publisher or author values are set to `Unknown`.
5. **Quantity:** `quantity` is a random number of copies, and `available_quantity` is set to the same value since no books are borrowed yet.
6. **No duplicates:** `firstOrCreate` checks the title and author first, so running a command twice does not create the same book again.
7. **API limits:** Each command only makes about 8 API requests (one per subject), waits 2 seconds between requests, and sends a `User-Agent` header with an app name and contact email, as Open Library requests.
8. **One-time import:** The data is stored in our own database, so the system never calls Open Library while running.

### Notes

- This is sample data for our school project, not real library inventory.
- Quantities are randomly generated.
- Some books may have `Unknown` publishers or be placed in a slightly different category than expected.

**Requirements:** PHP 8.2+, Composer, Node.js, Git, and MySQL (XAMPP) or a TiDB Cloud connection.

> **Note:** Node.js and `npm` are **not required** for now. Bootstrap 5 and Chart.js are loaded through CDN links in the Blade layout, so there is no `npm install` or `npm run build` step.
> The default Laravel starter files for Vite and Tailwind (`package.json`, `vite.config.js`, `resources/css`, `resources/js`) are still in the project and may be removed or used later. If the project switches to npm-based assets, this README will be updated.
>
> An internet connection is needed for pages to load their styles and charts.
