

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
