# Progression

`Progression` is a console PHP game where the player must find the missing number in an arithmetic progression.

## Requirements

- PHP 8.1 or newer
- Composer

## Installation

### Local installation

```bash
composer install
```

### Global installation with Composer

```bash
composer global require relflly/progression
```

After global installation add Composer's global `bin` directory to `PATH` and run:

```bash
progression
```

## Run

```bash
php bin/progression
```

## Development

Generate optimized autoload files:

```bash
composer dump-autoload -o
```

Check PSR-12:

```bash
php vendor/squizlabs/php_codesniffer/bin/phpcs --standard=PSR12 src bin/progression
```
