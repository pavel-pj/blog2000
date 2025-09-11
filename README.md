

# Запустить DEV

НУЖНО УСТАНАВИЛВАТЬ команды под юзером, не под sudo.
Добавить пользователя в группу юзер при необходимости.
Laravel - в worskpsace - exe

- make first-up-dev (первичный запуск можно запустить повторно при ошибке);

- make down-dev - выключить конейнеры
- make up-dev - запустить контейнеры
 

back : localhost:8080
front : localhost:5173


//Как использовать дамп :
в compose.dev.yaml контейнер postress :
- 1 /var/db1/db_backup.dump:/tmp/db1/db_backup.dump:ro
- Изменить расположение дампа.
- 2 docker exec -i vue-postgres-1 pg_restore -U laravel -d app -Fc /tmp/db1/db_backup.dump


# PRODUCTION 
- 1. cd frontend
- 2. npm run build
- 3. cp ./backend/.env.prod.example ./backend/.env 
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

