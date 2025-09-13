

# Запустить DEV

НУЖНО УСТАНАВИЛВАТЬ команды под юзером, не под sudo.
Добавить пользователя в группу юзер при необходимости.
Laravel - в worskpsace - exe

- make first-up-dev (первичный запуск можно запустить повторно при ошибке);

- make down-dev - выключить конейнеры
- make up-dev - запустить контейнеры


- установка дополнительных пакетов через root контейнера workspace : 
  docker compose -f compose.dev.yaml exec -u root workspace composer  require spatie/laravel-permission
 
- front : localhost:5173 
- back : localhost:8080

//Как использовать дамп :
в compose.dev.yaml контейнер postress :
 
### Postgres
- 1. Создать дамп, будет лежать папке /var/databases ( можно поменять папку)
docker compose -f compose.prod.yaml exec postgres pg_dump -U laravel -d app --clean --if-exists --schema=public > /var/databases/backup_$(date +%Y%m%d_%H%M%S).sql 
- Можно оставить по этому адресу, или переместить в другой.
- поменять расположение в compose.dev.yaml : 
- /var/databases/backup_20250913_111642.sql:/tmp/db1/db_backup.sql:ro
 
- 2. Восстановить бд
  если проект запущен - стереть все volume и контейнеры:
  - make clear-cache
  - docker compose -f compose.dev.yaml up -d postgres
  - подождать ~ 30 секунд
  - docker compose -f compose.dev.yaml exec postgres psql -U laravel -d app -f /tmp/db1/db_backup.sql
- 3. Запустить проект:
  - make up-dev  


# PRODUCTION 
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
- Необходимо запустить тестовый index.html на требуемом домене, черех nginx-host 
- установить sertbot. ВСЁ. гавлное - не менять расположение сертификатов. 
- сертификаты ssl прокидываются в docker compose volumes. 

