# Task04

Веб-приложение на Laravel для игры "Арифметическая прогрессия".

## Возможности

- одностраничный интерфейс для запуска новой партии;
- REST API для создания игр, просмотра истории и отправки ответа;
- хранение данных об играх в SQLite-базе `database/database.sqlite`.

## Установка

### Linux

Установка полностью автоматизирована через `Makefile`:

```bash
cd Task04
make install
```

Команда выполняет:

- установку PHP-зависимостей через `composer install`;
- создание файла `.env` из `.env.example`, если он отсутствует;
- создание файла SQLite-базы в каталоге `database`;
- генерацию ключа приложения при необходимости;
- запуск миграций.

### Windows

Если `make` недоступен, можно выполнить те же шаги вручную:

```bash
cd Task04
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
```

Перед миграцией убедитесь, что существует файл `database/database.sqlite`.

## Запуск

```bash
cd Task04
php artisan serve
```

После запуска приложение будет доступно по адресу [http://localhost:8000/](http://localhost:8000/).

## REST API

- `GET /api/games` - список всех игр;
- `GET /api/games/{id}` - подробная информация об игре;
- `POST /api/games` - создание новой игры;
- `POST /api/step/{id}` - отправка ответа для игры.

## Тесты

```bash
cd Task04
php artisan test
```
