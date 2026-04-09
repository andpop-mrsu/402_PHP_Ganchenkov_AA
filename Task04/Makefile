install:
	composer install
	test -f .env || cp .env.example .env
	mkdir -p database
	touch database/database.sqlite
	grep -q '^APP_KEY=base64:' .env || php artisan key:generate --ansi
	php artisan migrate --graceful --ansi
