<p align="center">
  <img src="https://i.postimg.cc/QNgXP3y1/Twitter-header-1-%282%29.png" alt="Bibliotheca" width="100%">
</p>

# Bibliotheca

A Library Management System built with Laravel. It handles book records, borrowing, returns, and a dashboard with live stats and charts.

## Tech Stack

- PHP / Laravel (Blade templating)
- Bootstrap
- Chart.js
- MySQL-compatible database (TiDB Cloud)

## Repository Rules

- ```main``` branch is the latest branch to work on, unless stated otherwise.
- Do not push to main. Create a separate branch, and it will be later be merged there.
- Follow branch naming ```yourname/your-feature``` to avoid confusions and determine who did which parts.
- Double check sensitive files in your ```gitignore```. Do not push ```env``` and other private files.


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

# 414 Books from OpenLibrary API
## Book Data Import (Do Not Run)


> **Please do not run these commands.** The `books` and `categories` tables were already populated. Running them again will add extra books and change the sample data we are using for testing. This is done to save time from manually creating dummy data.

The commands are kept in the project only to show how the sample data was created:

| Command | What it does |
|---|---|
| `php artisan books:import` | Imports general books (fiction, science, history, technology, etc.) |
| `php artisan books:import-filipino` | Imports Filipino-related books (literature, history, folklore, culture, etc.) |
| `php artisan books:import-more` | Imports more general and educational books (psychology, art, programming, comics, IT, nursing, education, engineering) |
| `php artisan books:import-kids-teens` | Imports books intended for younger audiences. |

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

---

## Requirements

Before installing the project, make sure you have the following:

- PHP 8.2 or newer
- Composer
- Git
- Beekeeper Studio (to view DB) or access to a TiDB Cloud database credentials/dashboard
- A code editor (VS Code or PhpStorm recommended)

An internet connection is also needed to load externally hosted frontend resources through CDN links.


---

## Installation and Setup

### 1. Clone the Repository

Open your terminal and navigate to your preferred project directory.

```bash
git clone https://github.com/anjlalxn/bibliotheca.git
```

Navigate into the Laravel application directory:

```bash
cd bibliotheca/bibliotheca-laravel
```

### 2. Install Dependencies

Install the PHP dependencies using Composer:

```bash
composer install
```

### 3. Configure the Environment

Create your local environment configuration file:

**Windows (Command Prompt):**

```bash
copy .env.example .env
```

**macOS / Linux:**

```bash
cp .env.example .env
```

Generate your Laravel application key:

```bash
php artisan key:generate
```


---

## Frontend Setup

No additional frontend installation is necessary.

The project uses:

- **Bootstrap** for layouts, components, and responsive styling.
- **Vanilla JavaScript** for client-side interactions.
- **Blade templates** for rendering dynamic pages.

Bootstrap resources are loaded through CDN links in the Blade layouts.

There is no need to run `npm install`, `npm run dev`, or `npm run build` for the current setup.

---


## Troubleshooting

| Problem | Possible Solution |
|---|---|
| `composer` is not recognized | Install Composer and add it to your system PATH. |
| `php` is not recognized | Install PHP and configure your system PATH. |
| Database connection error | Check your database credentials and ensure the database server is running. |
| Application key missing | Run `php artisan key:generate`. |
| Missing dependencies | Run `composer install` inside `bibliotheca-laravel`. |
| Tables do not exist | Check your database connection and run `php artisan migrate` if appropriate. |
| Styles are not loading | Check your internet connection and CDN availability. |

---

## Project Repository

GitHub: https://github.com/anjlalxn/bibliotheca


