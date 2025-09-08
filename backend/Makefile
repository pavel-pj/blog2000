install:
	composer install --no-interaction --prefer-dist --no-scripts
	composer dump-autoload
lint:
	composer exec --verbose phpcs -- --standard=PSR12 app
