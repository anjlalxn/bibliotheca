<p align="center">
  <img src="https://i.postimg.cc/QNgXP3y1/Twitter-header-1-%282%29.png" alt="Bibliotheca" width="100%">
</p>

#  Bibliotheca – Library Management System (Advanced WebDev Course Final Project)

A web-based Library Management System developed using **Laravel**. Bibliotheca provides a centralized platform for managing book records, monitoring book availability, handling borrowing and returns, and viewing library statistics through an administrative dashboard.

##  Features

- **Dashboard** – View library statistics, including book records, borrowing activity, and other library information through summary cards and charts.
- **Book Management** – Add, view, edit, search, and delete book records.
- **Category Management** – Organize books into categories for easier browsing and management.
- **Borrowing Management** – Record and manage book borrowing transactions.
- **Return Management** – Process returned books and update their availability.
- **Inventory Tracking** – Monitor total book quantities and available copies.
- **Search and Filtering** – Find book records more easily.

##  Technologies Used

| Technology | Purpose |
|---|---|
| PHP 8.2+ | Backend programming language |
| Laravel | PHP web application framework |
| MySQL / TiDB Cloud | Database management |
| Blade | Laravel templating engine |
| Bootstrap 5 | Frontend styling and responsive layout |
| JavaScript | Client-side interactions |
| Chart.js | Dashboard charts |
| Open Library Search API | Source of sample book information |

##  Requirements

Before running the project, make sure you have installed:

- PHP 8.2 or higher
- Composer
- Git
- Beekeeper Studio (Viewer for DB) or access to the configured TiDB Cloud database

**Note:** Node.js and npm are not required for the current version. Bootstrap 5 and Chart.js are loaded through CDN links.

##  Installation and Setup

### 1. Clone the Repository

```bash
git clone https://github.com/anjlalxn/bibliotheca.git
```

### 2. Open the Laravel Project

Navigate to the Laravel application directory:

```bash
cd bibliotheca/bibliotheca-laravel
```

### 3. Install PHP Dependencies

```bash
composer install
```

### 4. Configure Environment Variables

Create your local environment file:

```bash
cp .env.example .env
```

Open `.env` and configure the application and database credentials.
### 2. Open the Laravel Project

Navigate to the Laravel application directory:

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

### 3. Install PHP Dependencies

```

Replace the database placeholders with the credentials provided for the project.

**Important:** Keep your `.env` file private. Do not commit database credentials to GitHub.

### 5. Generate the Application Key

```bash
php artisan key:generate
```

### 6. Run the Application

```bash
php artisan serve
```

Open the application in your browser:
### 4. Configure Environment Variables

Create your local environment file:

```bash
cp .env.example .env
```

Open `.env` and configure the application and database credentials.

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
```

Replace the database placeholders with the credentials provided for the project.

**Important:** Keep your `.env` file private. Do not commit database credentials to GitHub.

### 5. Generate the Application Key

```text
http://127.0.0.1:8000
```

## 🗄️ Database Information

Bibliotheca uses a MySQL-compatible database hosted through TiDB Cloud.

The project database contains the existing book and category records used for demonstration and testing.

**Important:**
- Use the provided database credentials.
- The database has already been populated with sample records.
- Do not run the sample data import commands again.
- Do not run database migrations unless instructed by the project maintainers.

## 📖 Sample Book Data

The project uses the free **Open Library Search API** to retrieve sample bibliographic information, including:

- Book titles
- Authors
- Publishers
- Publication years

Custom Laravel Artisan commands were developed to retrieve books from different Open Library subjects and store the results in the project's database.

The API is used for the initial population of sample records. The application retrieves its book information from its own database during normal operation.

### Sample Data Import Commands

The following commands are included in the project for reference only.

### 6. Run the Application

```bash
php artisan serve
```

Open the application in your browser:

```text
http://127.0.0.1:8000
```

## 🗄️ Database Information

Bibliotheca uses a MySQL-compatible database hosted through TiDB Cloud.

The project database contains the existing book and category records used for demonstration and testing.

**Important:**
- Use the provided database credentials.
- The database has already been populated with sample records.
- Do not run the sample data import commands again.
- Do not run database migrations unless instructed by the project maintainers.

## 📖 Sample Book Data

The project uses the free **Open Library Search API** to retrieve sample bibliographic information, including:

- Book titles
- Authors
- Publishers
- Publication years

Custom Laravel Artisan commands were developed to retrieve books from different Open Library subjects and store the results in the project's database.

The API is used for the initial population of sample records. The application retrieves its book information from its own database during normal operation.

### Sample Data Import Commands

The following commands are included in the project for reference only.

| Command | What it does |
|---|---|
| `php artisan books:import` | Imports general books (fiction, science, history, technology, etc.) |
| `php artisan books:import-filipino` | Imports Filipino-related books (literature, history, folklore, culture, etc.) |
| `php artisan books:import-more` | Imports more general and educational books (psychology, art, programming, comics, IT, nursing, education, engineering) |
| `php artisan books:import-kids-teens` | Imports books intended for younger audiences. |
| `php artisan books:import-reference` | Imports books (encyclopedias, dictionaries, almanacs, etc.) used for general information. |
| `php artisan books:import-manga-comics` | Imports manga, comics, and other educational books. |


**Do not execute these commands.** They are retained to document how the initial sample dataset was generated.

### Sample Data Disclaimer

The book records are intended for academic demonstration and testing purposes. They do not represent actual library inventory.

- Book quantities are randomly generated.
- Some bibliographic information may be unavailable.
- Missing authors or publishers may be represented as `Unknown`.
- Book availability is maintained within the application's database.

##  Project Structure

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

##  License

This project is intended for educational and academic purposes.
---

## Project Repository

GitHub: https://github.com/anjlalxn/bibliotheca


