<div align="center">
  <img src="docs/rupa_bass_ascii.svg" width="650" alt="Stockify Banner" />

  <h1>Stockify</h1>

  <p>
    <strong>Enterprise Inventory and Stock Management System</strong>
  </p>

  <p>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Framework-Laravel-red?style=flat-square&logo=laravel" alt="Laravel" /></a>
    <a href="https://php.net"><img src="https://img.shields.io/badge/Language-PHP_8.2+-blue?style=flat-square&logo=php" alt="PHP" /></a>
    <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Styling-Tailwind_CSS-38B2AC?style=flat-square&logo=tailwind-css" alt="Tailwind CSS" /></a>
    <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License" />
  </p>
</div>

---

## Overview

Stockify is a web-based inventory and supply management platform engineered with the Laravel framework. The system provides structured workflows for tracking product flow, monitoring stock levels in real time, and maintaining catalog integrity with role-based access control.

## Key Capabilities

- **Catalog and Category Management:** Full CRUD operations for products, SKU assignment, categorized listing, and metadata handling.
- **Real-Time Stock Auditing:** Inbound and outbound stock logging with threshold alerts for low inventory.
- **Authentication and Security:** Protected authentication layers, session handling, and granular user authorization.
- **Modern Architecture:** Clean MVC design pattern, Eloquent ORM relationships, and responsive user interfaces powered by Tailwind CSS.

## System Prerequisites

Before deployment or local setup, verify that your environment meets the following specifications:

- PHP 8.2 or higher
- Composer (Dependency Manager for PHP)
- Node.js (v18+) and NPM
- Relational Database (MySQL, MariaDB, or PostgreSQL)

## Installation and Local Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/grendot-coder/stockify.git
   cd stockify
   ```

2. **Install application dependencies:**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configure environment settings:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database migration:**
   Configure your database credentials in the `.env` file (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), then execute:
   ```bash
   php artisan migrate
   ```

5. **Start local development server:**
   ```bash
   php artisan serve
   ```
   The application will be accessible at `http://localhost:8000`.

## Architecture and Tech Stack

- **Backend:** PHP, Laravel 11.x, Eloquent ORM
- **Frontend:** Blade Templating, Tailwind CSS, Vanilla JavaScript
- **Database:** MySQL / SQLite
- **Asset Bundler:** Vite

## Maintainer

Authored and maintained by **Grendy Agusti**  
- GitHub: [grendot-coder](https://github.com/grendot-coder)

## License

This project is licensed under the terms of the [MIT License](LICENSE).
