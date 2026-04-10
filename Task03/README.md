# Task03: Progression SPA on Slim

Single Page Application для игры "Арифметическая прогрессия" с REST API на Slim и хранением данных в SQLite.

## Структура

- `public/` - корень сайта.
- `public/index.php` - единственная точка входа backend на Slim.
- `public/index.html` - клиентское SPA.
- `public/assets/` - JavaScript и CSS.
- `src/` - backend-классы приложения.
- `db/` - файл базы данных SQLite.

## Установка зависимостей

```bash
cd Task03
composer install
```

## Запуск

```bash
php -S localhost:3000 -t public
```

После запуска приложение доступно по адресу:

```text
http://localhost:3000/
```

REST API:

- `GET /games`
- `GET /games/{id}`
- `POST /games`
- `POST /step/{id}`

Файл базы данных создается автоматически в `Task03/db/progression.sqlite`.
