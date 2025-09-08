FROM node

WORKDIR /var/www

# Копируем package.json и package-lock.json
COPY ./frontend/package*.json ./

# Устанавливаем зависимости
RUN npm install

# Копируем весь код фронтенда
COPY ./frontend/ .

# Expose порт Vite
EXPOSE 5173

# Запускаем dev server с хостом 0.0.0.0 для доступа извне
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]