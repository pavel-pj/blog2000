[![Laravel Tests](https://github.com/pavel-pj/blog2000/actions/workflows/main.yml/badge.svg)](https://github.com/pavel-pj/blog2000/actions/workflows/main.yml)


# 🚀 Blog Platform
 
## Development

* Устанавливать команды под юзером ubuntu, не под sudo.
* добавить пользователя в группу докер и запускать docker без sudo
 

### Новый проект
 
 первичный запуск можно запустить повторно при ошибке 
```make first-up-dev```
 
### Уже существует дамп postges 
  раскоментировать три строки в compose postgres :
#- /var/databases/blog/backup_20250913_114733.sql:/tmp/db1/db_backup.sql:ro
#- ./docker/backend/development/database/init-multiple-databases.sh:/docker-entrypoint-initdb.d/init-multiple-databases.sh:ro
#- ./docker/backend/development/database/restore-databases.sh:/docker-entrypoint-initdb.d/restore-databases.sh:ro

/var/databases/blog/backup_20250913_114733.sql - путь на ХОСТЕ к дампу дб ( см ниже)

 1) сначала запустить постгрес.
  make up-postgres
 2) через 15-20 секунд все отальное 
  make up-dev 

- Команды для работы : 
- make down-dev - выключить конейнеры
- make up-dev - запустить контейнеры
 


- установка дополнительных пакетов через root контейнера workspace : 
  docker compose -f compose.dev.yaml exec -u root workspace composer  require spatie/laravel-permission
- Команды для работы : 
- make down-dev - выключить конейнеры
- make up-dev - запустить контейнеры


- front : localhost:5173 
- back : localhost:8080

//Как использовать дамп :
в compose.dev.yaml контейнер postress :
 
#### Postgres
- 1. Создать дамп, будет лежать папке /var/databases ( можно поменять папку)
docker compose -f compose.prod.yaml exec postgres pg_dump -U laravel -d app --clean --if-exists --schema=public > /var/databases/backup_$(date +%Y%m%d_%H%M%S).sql 
- Можно оставить по этому адресу, или переместить в другой.
- поменять расположение в compose.dev.yaml : 
- /var/databases/backup_20250913_111642.sql:/tmp/db1/db_backup.sql:ro

- во время запуска создается БД из дампа + из дампа тестовая база.
- Если не нужен дамп, закомментировать три строки : 
  - /var/databases/blog/backup_20250913_114733.sql:/tmp/db1/db_backup.sql:ro
  - ./docker/backend/development/database/init-multiple-databases.sh:/docker-entrypoint-initdb.d/init-multiple-databases.sh:ro
  - ./docker/backend/development/database/restore-databases.sh:/docker-entrypoint-initdb.d/restore-databases.sh:ro
 
#### ТЕСТЫ :
- тестовая база автоматически запускается и копируется из дампа.
- Запустить новые миграции на тестовой базе :
  make test-migrate


## Production
- 0. cp frontend/.env.prod.example frontend/.env
- 1. cd frontend
  2. npm install
- 2. npm run build
  2. cp docker/backend/nginx/nginx.example.conf docker/backend/nginx/nginx.conf
   изенить в файле nginx.conf domen.com на реальный домен ( без http/https)
- 3. cp backend/.env.prod.example backend/.env 
  - изменить site на реальный домен:
  - ВНИМАТЕЛЬНО - не добавлять и не убирать https://
  - APP_URL=https://site
  - SESSION_DOMAIN=site
  - SANCTUM_STATEFUL_DOMAINS=site
  - CORS_ALLOWED_ORIGINS=https://site
- 4. cd backend
- 5. composer install
- 6. php artisan key:generate
  
- Настроить ssl отдельно - через sertbot.
- Необходимо запустить тестовый index.html на требуемом домене. Остановить все контейнеры на хосте.
- запустить хостовый nginx, настроить config nginx для конкертно домена ( в любую папку,
- главное, чтобы 80 порт домена показывал содержимое index.html) 
- установить sertbot.ЗАПУСТИТЬ sertbot. После установки сертификатов ВЫКЛЮЧИТЬ nginx на хосте.
-  ВСЁ. гавлное - не менять расположение сертификатов. 
- сертификаты ssl прокидываются в docker compose volumes. 

