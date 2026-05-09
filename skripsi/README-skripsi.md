# Inventory & Sales Management System

A web-based admin system built as a Bachelor's final project (Skripsi) at Universitas Tarumanagara. The system helps businesses manage product stock, sales transactions, restocking, and financial records in one centralized dashboard.

---

## Features

- **Stock Tracking & Alerts** — Monitor product inventory levels in real time with automatic low-stock alerts
- **Sales Input** — Record customer purchases and manage transaction history
- **Restocking Management** — Log and track incoming stock from distributors
- **Distributor Management** — Maintain a list of suppliers and distributor details
- **Customer Management** — Store and manage customer records
- **Tax Calculation** — Automatic tax computation on transactions
- **Authentication** — Secure admin login system with role-based access

---

## Tech Stack

- [Laravel](https://laravel.com/) (PHP Framework)
- JavaScript
- MySQL
- Blade Templating Engine
- Bootstrap / CSS

---

## Getting Started

### Prerequisites

- PHP 8+
- Composer
- MySQL
- Node.js & npm

### Installation

```bash
git clone https://github.com/Lunaristt/<your-repo-name>.git
cd <your-repo-name>
composer install
npm install
```

### Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Database Migration

```bash
php artisan migrate
php artisan db:seed
```

### Run Development Server

```bash
php artisan serve
```

Open [http://localhost:8000](http://localhost:8000) in your browser.

---

## Project Structure

```
app/
├── Http/Controllers/     # Business logic controllers
├── Models/               # Eloquent models
resources/
├── views/                # Blade templates
routes/
├── web.php               # Web routes
database/
├── migrations/           # Database schema
```

---

## Key Modules

| Module | Description |
|---|---|
| Stock Management | Track inventory levels and trigger alerts when stock is low |
| Sales | Record purchases, assign to customers, calculate totals with tax |
| Restocking | Log incoming stock and link to distributors |
| Distributors | Manage supplier information |
| Customers | Store buyer records and purchase history |
| Authentication | Admin login with session management |
