# Sped Delivery – Round 1 Submission

## 🚀 Overview
Backend system for a food delivery platform with:
- Delivery zone validation (radius-based)
- Order placement with geospatial checks
- Nearest delivery person assignment
- API authentication via Laravel Sanctum
- Fully tested with PHPUnit

## 🔧 Tech Stack
- **Framework**: Laravel v12
- **Database**: MySQL (spatial functions)
- **Auth**: Laravel Sanctum
- **Testing**: PHPUnit
- **Geo Logic**: MySQL `ST_Distance_Sphere`

## 📦 Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan test
php artisan serve
