.PHONY: build-dev up-dev down-dev clear-all bash-pro clear-cache lint

build-pro:
	docker compose -f compose.prod.yaml build
rebuild-pro:
	docker compose -f compose.prod.yaml down
	docker compose -f compose.prod.yaml build --no-cache
	docker compose -f compose.prod.yaml up -d
up-pro:
	docker compose -f compose.prod.yaml up -d
down-pro:
	sudo docker compose -f compose.prod.yaml down
clear-pro:
	docker compose -f compose.prod.yaml down --remove-orphans
	docker system prune -f
	sudo systemctl restart docker
bash-pro:
	docker compose -f compose.prod.yaml exec php-fpm bash






### DEVELOPMENT

logs:
	docker compose -f compose.dev.yaml logs php-fpm
lint-check:
	docker compose -f compose.dev.yaml exec -u root workspace bash -c "composer lint"	
lint:
	docker compose -f compose.dev.yaml exec -u root workspace bash -c "composer lintfix"

build-dev:
	docker compose -f compose.dev.yaml build
up-postgres:
	docker compose -f compose.dev.yaml up -d postgres

first-up-dev2:
	sudo chmod -R 775 backend/storage/   
	sudo chmod -R 775 backend/bootstrap/cache/ 
	docker compose -f compose.dev.yaml up -d
	docker compose -f compose.dev.yaml exec -u root workspace composer install --optimize-autoloader --no-interaction --no-progress 
	docker compose -f compose.dev.yaml exec -u root workspace chown -R www:www /var/www/vendor 
	docker compose -f compose.dev.yaml exec -u root workspace chmod -R 775 /var/www/vendor 
	docker compose -f compose.dev.yaml exec -u root workspace php artisan key:generate 
	docker compose -f compose.dev.yaml exec -u root workspace php artisan migrate 
	docker compose -f compose.dev.yaml up -d 
	docker compose -f compose.dev.yaml exec -u root php-fpm chown -R www:www /var/www/storage/ 
	docker compose -f compose.dev.yaml exec -u root php-fpm chmod -R 775 /var/www/storage/ 
	docker compose -f compose.dev.yaml exec -u root php-fpm chmod -R 775 /var/www/bootstrap/cache/ 

up-dev:
	docker compose -f compose.dev.yaml up -d


down-dev:
	 docker compose -f compose.dev.yaml down
stop-dev:
	 docker compose -f compose.dev.yaml stop	 
bash-dev:
	docker compose -f compose.dev.yaml exec -u root workspace bash

pgsql-dev:
	docker exec -it  vue-postgres-1 psql -U laravel -d app	
pgsql-dev-log:	
	docker compose -f compose.dev.yaml logs postgres

## ТЕСТЫ : 
test-migrate:
	docker compose -f compose.dev.yaml exec workspace php artisan test:migrate
#	docker compose -f compose.dev.yaml exec workspace php artisan test:migrate --fresh
#	docker compose -f compose.dev.yaml exec workspace php artisan db:seed --database=testing

#test:
#	docker compose -f compose.dev.yaml exec workspace php artisan test
test:
	docker compose -f compose.dev.yaml exec workspace php artisan test --coverage	
test-%:
	docker compose -f compose.dev.yaml exec workspace vendor/bin/phpunit tests/Feature/$(subst -,/,$*).php


clear-cache:
	# Останавливаем все контейнеры (игнорируем ошибки если нет контейнеров)
	-docker stop $$(docker ps -aq)
	# Удаляем все контейнеры
	-docker rm $$(docker ps -aq)
	# Удаляем все volumes (игнорируем ошибки)
	-docker volume rm $$(docker volume ls -q) 2>/dev/null || true
	# Удаляем кастомные сети
	-docker network rm $$(docker network ls -q --filter type=custom) 2>/dev/null || true
	# Очищаем builder cache
	docker builder prune -f
	# Очищаем system
	docker system prune -f
	# Удаляем папку dist 
#	rm -rf frontend/dist

front:
	cd frontend && npm run build

clear-all:
	docker stop $$(docker ps -aq) 2>/dev/null || true
	docker rm -f $$(docker ps -aq) 2>/dev/null || true
	docker rmi -f $$(docker images -aq) 2>/dev/null || true
	docker volume rm -f $$(docker volume ls -q) 2>/dev/null || true
	docker network prune -f
	docker system prune -a -f --volumes

#install:
#	 mkdir -p backend/vendor backend/node_modules
#	 chown -R $(USER):$(USER) .
#	 find . -type d -exec chmod 755 {} \;
#	 find . -type f -exec chmod 644 {} \;
#	 chmod -R 755 backend/node_modules/
#	 chmod 755 backend/public/

first-up-dev:
	sudo cp backend/.env.dev.example backend/.env 
	sudo cp frontend/.env.dev.example frontend/.env 
	sudo chmod -R 775 backend/storage/ 
	sudo chmod -R 775 backend/bootstrap/cache/ 
	docker compose -f compose.dev.yaml up -d
	docker compose -f compose.dev.yaml exec -u root workspace composer install --optimize-autoloader --no-interaction --no-progress
	docker compose -f compose.dev.yaml exec -u root workspace chown -R www:www /var/www/vendor 
	docker compose -f compose.dev.yaml exec -u root workspace chmod -R 775 /var/www/vendor 
	docker compose -f compose.dev.yaml exec -u root workspace php artisan key:generate 
	docker compose -f compose.dev.yaml exec -u root workspace php artisan migrate 
	docker compose -f compose.dev.yaml up -d
	docker compose -f compose.dev.yaml exec -u root php-fpm chown -R www:www /var/www/storage/ 
	docker compose -f compose.dev.yaml exec -u root php-fpm chmod -R 775 /var/www/storage/
	docker compose -f compose.dev.yaml exec -u root php-fpm chmod -R 775 /var/www/bootstrap/cache/
 

