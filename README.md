# Laravel RESTful API

A RESTful API built with Laravel 13 and PHP 8.2.

---

## Requirements

- PHP >= 8.2  
- Composer  
- PostgreSQL  
- Laravel 13  

---

## Installation

```bash
git clone https://github.com/frediansimanjuntak/laravel-test-api.git
cd laravel-test-api
composer install
cp .env.example .env
php artisan key:generate
```

---

## Environment Configuration

Edit `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel_rest_api
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🗄️ Database Setup

```bash
php artisan migrate --seed
```

---

## ▶️ Run Application

```bash
php artisan serve
```

---

## 📌 API Routes

### Auth
- POST /api/auth/login
- POST /api/auth/logout

### Users
- GET /api/v1/users
- POST /api/v1/users
- GET /api/v1/users/{id}
- PUT /api/v1/users/{id}
- DELETE /api/v1/users/{id}

---

## 📄 Sample JSON

### Login

URL:
```bash
http://localhost:8000/api/auth/login
```

Request:
```json
{
  "email": "john@example.com",
  "password": "password"
}
```

Response:
```json
{
    "status": "success",
    "message": "Request successful.",
    "data": {
        "token": "7|0S1kkzMMXR5BXgcsmEWtyPjeomfm8grQa5TBKZu610b862c8",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "administrator",
            "is_active": true,
            "orders_count": 0,
            "can_edit": true,
            "email_verified_at": null,
            "created_at": "2026-04-24T06:05:06+00:00",
            "updated_at": "2026-04-24T08:34:27+00:00"
        }
    }
}
```

### Get Users

URL:
```bash
GET http://localhost:8000/api/v1/users?search=&sort_by=&sort_dir=&per_page=2&page=1
```

Request:
```json
{}
```

Response:
```json
{
    "status": "success",
    "message": "Request successful.",
    "data": [
        {
            "id": 2,
            "name": "Cielo McCullough DDS",
            "email": "fcartwright@example.com",
            "role": "administrator",
            "is_active": true,
            "orders_count": 10,
            "can_edit": false,
            "email_verified_at": "2026-04-24T05:57:46+00:00",
            "created_at": "2026-04-24T05:57:46+00:00",
            "updated_at": "2026-04-24T05:57:46+00:00"
        },
        {
            "id": 1,
            "name": "Alverta Von",
            "email": "clair.boehm@example.com",
            "role": "administrator",
            "is_active": true,
            "orders_count": 6,
            "can_edit": false,
            "email_verified_at": "2026-04-24T05:57:46+00:00",
            "created_at": "2026-04-24T05:57:46+00:00",
            "updated_at": "2026-04-24T05:57:46+00:00"
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 10,
        "per_page": 2,
        "total": 20,
        "from": 1,
        "to": 2
    },
    "links": {
        "first": "http://localhost:8000/api/v1/users?per_page=2&sort_dir=asc&page=1",
        "last": "http://localhost:8000/api/v1/users?per_page=2&sort_dir=asc&page=10",
        "prev": null,
        "next": "http://localhost:8000/api/v1/users?per_page=2&sort_dir=asc&page=2"
    }
}
```


### Create User

URL:
```bash
POST http://localhost:8000/api/v1/users
```

Request:
```json
{
    "name": "Thomas",
    "email": "thomas@example.com",
    "password": "Pass1234!",
    "role": "user"
    //  # defaults role: user
}
```

Response:
```json
{
    "status": "success",
    "message": "Request successful.",
    "data": {
        "id": 25,
        "name": "Thomas",
        "email": "thomas@example.com",
        "role": "user",
        "is_active": true,
        "orders_count": 0,
        "can_edit": true,
        "email_verified_at": null,
        "created_at": "2026-04-24T11:25:46+00:00",
        "updated_at": "2026-04-24T11:25:46+00:00"
    }
}
```

---

## 📄 License

MIT
