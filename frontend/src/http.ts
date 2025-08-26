import axios from 'axios';
import type { InternalAxiosRequestConfig } from 'axios';



const http = axios.create({
  baseURL: 'http://localhost:8080/api/',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
});

// Перехватчик для автоматической отправки CSRF-токена
http.interceptors.request.use((config: InternalAxiosRequestConfig) => {
  const method = config.method?.toLowerCase();
  if (method && ['post', 'put', 'patch', 'delete'].includes(method)) {
    config.headers = config.headers || {}; // Гарантируем, что headers существует
   // config.headers['X-CSRF-TOKEN'] = localStorage.getItem('csrf_token') || '';
  }
  return config;
});

// Добавляем токен к каждому запросу
http.interceptors.request.use((config: InternalAxiosRequestConfig) => {
  const token = localStorage.getItem('auth_token'); // Токен из localStorage
  if (token) {
    config.headers = config.headers || {};
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});


export default http;

