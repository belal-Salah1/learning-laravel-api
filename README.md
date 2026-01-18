<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# laravel-image-prompt-suite

Full-featured Laravel image→prompt service: upload images, validate and sanitize files, then generate detailed image-generation prompts using OpenAI. Includes authentication, upload storage, validation, example requests, and recommended production practices.

## Features
- Posts CRUD: create, read, update, and delete posts with validation and ownership checks
- Sanctum-based API authentication and optional session support
- Secure upload validation (`file`, `image`, `mimes`, `dimensions`, size limits)
- OpenAI integration: analyze images and produce detailed prompts
- Safe filename sanitization and public storage for uploads
- Rate limiting handling
- api versioning
- Suggested caching/dedupe to avoid redundant processing
- Example curl/Postman requests and setup instructions

## Quick Start

1. Copy `.env.example` to `.env` and set your environment variables (database, `services.openai.key`, etc.).
2. Install dependencies:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

3. Configure storage and run migrations:

```bash
php artisan storage:link
php artisan migrate
```

4. (Optional) Run queue worker for background jobs:

```bash
php artisan queue:work
```

5. Start the server:

```bash
php artisan serve
```

## Environment variables

- `APP_URL` — application base URL
- `DB_*` — database connection
- `FILESYSTEM_DRIVER` — recommended `public` for uploads
- `SERVICES_OPENAI_KEY` or `services.openai.key` — your OpenAI API key

Provide a `.env.example` with non-sensitive defaults and keep your real `.env` out of the repository.

## API Endpoints (examples)

- POST /api/register — create user (Sanctum)
- POST /api/login — obtain token
- POST /api/upload-image — multipart form upload: `image` field (authenticated)
- POST /api/logout — revoke token

Posts endpoints (example):

- GET /api/posts — list posts (public or authenticated based on config)
- POST /api/posts — create a post (authenticated)
- GET /api/posts/{id} — view a single post
- PUT/PATCH /api/posts/{id} — update a post (owner only)
- DELETE /api/posts/{id} — delete a post (owner only)

Example curl (upload image):

```bash
curl -X POST "http://127.0.0.1:8000/api/upload-image" \
	-H "Authorization: Bearer <TOKEN>" \
	-F "image=@/path/to/photo.jpg"
```

## Recommended Production Practices

- Run image processing as queued jobs to handle OpenAI rate limits and retries.
- Respect OpenAI rate limits (backoff / Retry-After header).
- Use object storage (S3) for uploads in production.
- Add monitoring: retry/failed job alerts and request metrics.
- Keep secrets out of repo and use CI secrets for deployments.

## Tests & CI

Add PHPUnit/Pest tests for request validation, controller responses, and queued job behavior. Use GitHub Actions for CI to run tests and static analysis.

## License

MIT


