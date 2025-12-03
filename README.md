# iPhone Cases Website

Individual Web Programming Project - HCMUT

## Stack

- **Backend:** PHP 8.2, MySQL 8.0
- **Frontend:** Vanilla HTML/CSS/JS
- **Environment:** Docker Compose (PHP-Apache, MySQL, phpMyAdmin)

## Prerequisites
- Docker/Podman with Compose.

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
icases-website
├── database
│   └── init.sql
├── src
│   ├── app
│   │   ├── controllers
│   │   ├── models
│   │   └── views
│   │       ├── components
│   │       │   ├── footer.php
│   │       │   └── header.php
│   │       └── *.php
│   ├── configs
│   └── public
│       ├── assets
│       ├── .htaccess
│       └── index.php
├── .env
├── compose.yml
└── Dockerfile
```

## Features

- Responsive layout (desktop/tablet/mobile)
- Product catalog with sorting, filtering, pagination
- AJAX search with dropdown suggestions
- Category navigation with breadcrumbs
- Store availability with Google Maps links
- User authentication (register, login, password reset)
- Shopping cart system with add/update/remove actions
- Complete checkout flow with order confirmation
- Security hardening: login rate limiting, enhanced password validation, session security (HttpOnly, SameSite, strict mode), Apache security headers
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
