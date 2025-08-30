

# Запустить DEV

- cp ./backend/.env.example ./backend/.env
- cp ./frontend/.env.example ./frontend/.env
- make build-dev
- make up-dev
- make install
- make bash-dev
  -> composer install
  -> php artisan key:generate
  -> php artisan migrate

back : localhost:8080
front : localhost:5173


//Как использовать дамп :
в compose.dev.yaml контейнер postress :
- 1 /var/db1/db_backup.dump:/tmp/db1/db_backup.dump:ro
- Изменить расположение дампа.
- 2 docker exec -i vue-postgres-1 pg_restore -U laravel -d app -Fc /tmp/db1/db_backup.dump
