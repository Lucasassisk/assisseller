# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Tech Stack

Laravel 13.8 (PHP 8.3+) backend with Blade templating, Alpine.js for interactivity, Tailwind CSS for styling, Vite for asset bundling, and SQLite as the default database. Authentication via Laravel Breeze.

## Commands

```bash
# First-time setup: installs deps, generates APP_KEY, runs migrations, builds assets
composer setup

# Development: starts Laravel server, queue listener, pail log tail, and Vite HMR concurrently
composer run dev

# Run tests (PHPUnit, uses in-memory SQLite)
composer test

# Build frontend assets for production
npm run build
```

## Architecture

This is an e-commerce storefront with an admin dashboard.

**Routing split** (`routes/web.php`):
- Store frontend — public routes: `/`, `POST /checkout/order`, `POST /checkout/coupon`, `POST /checkout/track`
- Admin dashboard — auth-protected under `/admin`: orders, products, customers, coupons, gallery, settings

**Core models**: `Product`, `Order`, `OrderItem`, `Customer`, `Coupon`, `Gallery`, `Setting`

**Admin controllers** live in `app/Http/Controllers/Admin/`; store controllers in `app/Http/Controllers/Store/`.

**Payments**: Stripe is configured via `app/Models/Setting.php` (keys stored in DB, editable at `/admin/settings`).

**Uploads**: User-uploaded images land in `uploads/` and are managed through `GalleryController` with product assignment support.

**Queue**: Uses the `database` connection — the queue worker is included in `composer run dev`.

**Tests**: PHPUnit suites in `tests/Unit/` and `tests/Feature/`; test environment uses in-memory SQLite (configured in `phpunit.xml`).
