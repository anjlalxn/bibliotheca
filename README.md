# Bibliotheca – Library Management System

**Advanced Web Development Course – Final Project**

<p align="center">
  <img src="https://i.postimg.cc/QNgXP3y1/Twitter-header-1-%282%29.png" alt="Bibliotheca Banner" width="100%">
</p>

Bibliotheca is a web-based Library Management System developed using **Laravel**. It provides a centralized platform for managing book records, monitoring book availability, handling borrowing and returns, and viewing library statistics through an administrative dashboard.

## Features

- **Dashboard** – View library statistics, borrowing activity, and other library information through summary cards and charts.
- **Book Management** – Add, view, edit, search, and delete book records.
- **Category Management** – Organize books into categories for easier browsing and management.
- **Borrowing Management** – Record and manage book borrowing transactions.
- **Return Management** – Process returned books and update their availability.
- **Inventory Tracking** – Monitor total book quantities and available copies.
- **Search and Filtering** – Find book records more easily.

## Technologies Used

| Technology | Purpose |
|---|---|
| PHP 8.2+ | Backend programming language |
| Laravel | PHP web application framework |
| MySQL / TiDB Cloud | Database management |
| Blade | Laravel templating engine |
| Bootstrap 5 | Frontend styling and responsive layout |
| JavaScript | Client-side interactions |
| Chart.js | Dashboard charts and data visualization |
| Open Library Search API | Source of sample book information |

## Requirements

Before running the project, make sure you have installed:

- PHP 8.2 or higher
- Composer
- Git
- Beekeeper Studio (optional, for database viewing) or access to the configured TiDB Cloud database

**Note:** Node.js and npm are not required for the current version. Bootstrap 5 and Chart.js are loaded through CDN links.

## Installation and Setup

### 1. Clone the Repository

```bash
git clone https://github.com/anjlalxn/bibliotheca.git
```

### 2. Navigate to the Laravel Project

```bash
cd bibliotheca/bibliotheca-laravel
```

### 3. Install PHP Dependencies

```bash
composer install
```

### 4. Configure Environment Variables

Create your local environment file by copying `.env.example`.

**Windows (Command Prompt):**
```cmd
copy .env.example .env
```

**Windows (PowerShell):**
```powershell
Copy-Item .env.example .env
```

**Git Bash / macOS / Linux:**
```bash
cp .env.example .env
```

Open the generated `.env` file and configure the application and database credentials.

```env
APP_NAME=Bibliotheca
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=YOUR_DATABASE_HOST
DB_PORT=4000
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=YOUR_DATABASE_USERNAME
DB_PASSWORD=YOUR_DATABASE_PASSWORD

MYSQL_ATTR_SSL_CA=YOUR_CERT_FILE_PATH
```

Replace the database placeholders with the credentials provided for the project. Configure the SSL certificate path according to the supplied database certificate.

**Important:**
- Keep your `.env` file private.
- Never commit database credentials or SSL certificates containing sensitive information to GitHub.
- Make sure your database connection settings are correct before running the application.

### 5. Generate the Application Key

```bash
php artisan key:generate
```

### 6. Run the Application

Start the Laravel development server:

```bash
php artisan serve
```

Open the application in your browser:

```text
http://127.0.0.1:8000
```

## Database Information

Bibliotheca uses a MySQL-compatible database hosted through **TiDB Cloud**.

The project database already contains the existing book and category records used for demonstration and testing.

**Important Database Instructions:**

- Use the provided database credentials.
- The database has already been populated with sample records.
- Do not run the sample data import commands again.
- Do not run database migrations unless explicitly instructed by the project maintainers.

No database migration or sample data import is required for the existing project setup.

## Sample Book Data

Bibliotheca uses the free **[Open Library Search API](https://openlibrary.org/developers/api)** to retrieve sample bibliographic information, including:

- Book titles
- Authors
- Publishers
- Publication years

Custom Laravel Artisan commands were developed to retrieve books from different Open Library subjects and store the retrieved information in the project's database.

The API was used for the initial population of sample records. During normal application operation, book information is retrieved from the project's own database.

### Sample Data Import Commands

The following Artisan commands are included for documentation and reference purposes.

| Command | Description |
|---|---|
| `php artisan books:import` | Imports general books, including fiction, science, history, and technology. |
| `php artisan books:import-filipino` | Imports Filipino-related books, including literature, history, folklore, and culture. |
| `php artisan books:import-more` | Imports additional general and educational books, including psychology, art, programming, comics, IT, nursing, education, and engineering. |
| `php artisan books:import-kids-teens` | Imports books intended for younger audiences. |
| `php artisan books:import-reference` | Imports reference materials, including encyclopedias, dictionaries, and almanacs. |
| `php artisan books:import-manga-comics` | Imports manga, comics, and other educational books. |

**Do not execute these commands.** They are retained to document how the initial sample dataset was generated. The database has already been populated.

### Sample Data Disclaimer

The book records are intended for academic demonstration and testing purposes. They do not represent actual library inventory.

- Book quantities are randomly generated.
- Some bibliographic information may be unavailable.
- Missing authors or publishers may be represented as `Unknown`.
- Book availability is maintained within the application's database.

## Project Structure

```text
bibliotheca/
│
├── bibliotheca-laravel/
│   ├── app/
│   │   ├── Console/
│   │   │   └── Commands/
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   └── Models/
│   │
│   ├── database/
│   ├── public/
│   ├── resources/
│   │   └── views/
│   ├── routes/
│   ├── storage/
│   ├── .env.example
│   └── artisan
│
└── README.md
```

## Third-Party Notices & Attributions

Bibliotheca uses third-party frameworks, libraries, fonts, icons, APIs, and other resources to support its functionality and design. This section acknowledges these resources and their respective licenses or attribution requirements.

### Frameworks and Libraries

| Technology | Purpose | License |
|---|---|---|
| [Laravel](https://laravel.com/) | Backend web application framework | MIT |
| [Bootstrap 5](https://getbootstrap.com/) | Responsive frontend styling | MIT |
| [Chart.js](https://www.chartjs.org/) | Dashboard charts and data visualization | MIT |
| [PHP](https://www.php.net/) | Backend programming language | PHP License |

### Icons and Visual Assets

| Resource | Purpose | Attribution |
|---|---|---|
| [Business Library Books Icon](https://icon-icons.com/icon/business-library-books/230) | Application icon / visual asset | Nucleoapp via Icon-Icons.com |

**Icon Attribution:**

Business Library Books Icon by Nucleoapp, sourced from [Icon-Icons.com](https://icon-icons.com/authors/230-nucleoapp).

### Fonts and Typography

| Font | Purpose | Source | License |
|---|---|---|---|
| [Figtree](https://fonts.google.com/specimen/Figtree) | Interface typography | Google Fonts | SIL Open Font License 1.1 |
| [Momo Display](https://fonts.google.com/) | Display typography and headings | Google Fonts | Verify exact font license |
| [Inter](https://fonts.google.com/specimen/Inter) | Interface typography | Google Fonts | SIL Open Font License 1.1 |

### External APIs and Data Sources

| Resource | Purpose | Attribution |
|---|---|---|
| [Open Library](https://openlibrary.org/) | Retrieves book metadata for populating sample book records | Open Library / Internet Archive |

**Open Library Attribution**

Bibliotheca uses the Open Library Search API to retrieve bibliographic information, including book titles, authors, publishers, and publication years.

The retrieved information is used to populate sample book records for demonstration and testing purposes.

Open Library is a project of the [Internet Archive](https://archive.org/).

- API Documentation: https://openlibrary.org/developers/api
- Official Website: https://openlibrary.org/

## License

This project is intended for educational and academic purposes.

## Project Repository

[GitHub – Bibliotheca](https://github.com/anjlalxn/bibliotheca)
