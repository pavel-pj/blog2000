# Стадия сборки
FROM node as build-stage
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Стадия production
FROM nginx:stable-alpine as production-stage
# Копируем собранные статические файлы из стадии сборки
COPY --from=build-stage /app/dist /usr/share/nginx/html
# Копируем наш конфиг Nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]