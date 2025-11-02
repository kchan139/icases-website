# iPhone Cases Website

Individual Web Programming Project - HCMUT

## Stack

- **Backend:** PHP 8.2, MySQL 8.0
- **Frontend:** Vanilla HTML/CSS/JS
- **Container:** Docker Compose (PHP-Apache, MySQL, phpMyAdmin)

## Prerequisites

Requires Docker/Podman with Compose.

## Quick Start

1. **Setup environment:**
```bash
cp .env.example .env
```

2. **Start services:**
```bash
docker compose up -d
```

3. **Access:**
- Website: [http://localhost:8080](http://localhost:8080)
- phpMyAdmin: [http://localhost:8888](http://localhost:8888) (root/root)

## Default Login

- Email: `user@user.com`
- Password: `password`

## Project Structure

```
src
├── app
│   ├── controllers/
│   ├── models/
│   └── views/
├── configs
│   ├── apache.conf
│   ├── database.php
│   └── php.ini
└── public
    ├── api/
    ├── assets/
    └── index.php
```

## Features

- Responsive layout (desktop/tablet/mobile)
- Product catalog with sorting, filtering, pagination
- AJAX search with dropdown suggestions
- Category navigation with breadcrumbs
- Store availability with Google Maps links
- User authentication (register, login, password reset)
- SEO: semantic HTML, meta tags, friendly URLs

## Development

**Restart all containers:**
```bash
./restart.sh
```
> Check what it does before executing.

**View logs:**
```bash
docker compose logs -f php
```

**Access MySQL:**
```bash
docker exec -it mysql_ip_cases mysql -u root -p
```

## Specification

See full project spec in `docs/readme.md`.
