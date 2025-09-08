# Stage 2: Nginx production image
FROM nginx:alpine

# Удалите дефолтный конфиг
RUN rm /etc/nginx/conf.d/default.conf

# Создаем директории для SSL сертификатов
RUN mkdir -p /etc/ssl/certs

# Копируйте ваш кастомный конфиг
COPY ./docker/backend/production/nginx/nginx.conf /etc/nginx/conf.d/default.conf

#COPY --from=php-fpm-production /var/www/public /var/www/public - не работает
# ИСПРАВЛЕНО: Используем правильное имя стадии
#COPY --from=builder /var/www/public /var/www/public
# Создаем пустую директорию, файлы будут монтироваться через volumes
RUN mkdir -p /var/www/public

# Set the working directory to the public folder
WORKDIR /var/www/public

# Expose port 80 and start Nginx
EXPOSE 80
EXPOSE 443
CMD ["nginx", "-g", "daemon off;"]