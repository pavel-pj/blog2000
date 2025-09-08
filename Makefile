.PHONY: build-dev up-dev down-dev clear-all bash-pro clear-cache

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
php-log:
	docker compose -f compose.dev.yaml logs php-fpm


build-dev:
	docker compose -f compose.dev.yaml build
up-dev:
	docker compose -f compose.dev.yaml up -d
down-dev:
	sudo docker compose -f compose.dev.yaml down -v
rebuild-dev:
	sudo docker compose -f compose.dev.yaml down
	sudo docker compose -f compose.dev.yaml build --no-cache
	sudo docker compose -f compose.dev.yaml up -d
	
bash-dev:
	docker compose -f compose.dev.yaml exec workspace bash

pgsql-dev:
	docker exec -it  vue-postgres-1 psql -U laravel -d app	
pgsql-dev-log:	
	docker compose -f compose.dev.yaml logs postgres

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

install:
	sudo mkdir -p backend/vendor backend/node_modules
	sudo chown -R $(USER):$(USER) .
	sudo find . -type d -exec chmod 755 {} \;
	sudo find . -type f -exec chmod 644 {} \;
	sudo chmod -R 755 backend/node_modules/
	sudo chmod 755 backend/public/
 

