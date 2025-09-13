import { defineStore } from 'pinia';
import http from '@/http';
import { ref, Ref } from 'vue';

export const useAuthStore = defineStore('auth', ()=> {

  interface User {
    id:string,
    name:string,
    email:string,
  }

  interface LoginFormData {
    email:string,
    password:string,
    password_confirm?:string,
  }

  interface RoleData {
    [key:string] : string[]
  }

  interface AuthResponse {
    token:string,
    user: User,
    roles: RoleData,
  }

  const user : Ref<User | null > = ref(null);
  const token : Ref<string | null > = ref(localStorage.getItem('auth_token'));

  // Новый метод для проверки аутентификации
  const createUser = async(formData: LoginFormData): Promise<AuthResponse> => {
    try {
      // 1. Запрашиваем CSRF-куки (если нужно)
      await http.get('/sanctum/csrf-cookie');

      // 2. Отправляем логин/пароль
      const {data} = await http.post('/register', formData);

      // 3. Сохраняем токен и пользователя
      token.value = data.token;
      user.value = data.user;
      
      localStorage.setItem('auth_token', data.token);

      return data;
    } catch (error) {
      console.error('Registration failed:', error.response?.data || error.message);
      throw error;
    }
  }
  const  login = async (formData: LoginFormData): Promise<AuthResponse>=> {
     
    try {
      
      await http.get('/sanctum/csrf-cookie');
      
      // 2. Отправляем логин/пароль
      const {data} = await http.post('/login', formData);

      // 3. Сохраняем токен и пользователя
      token.value = data.token;
      user.value = data.user;
      localStorage.setItem('auth_token', data.token);

      return data;
    } catch (error) {
       
      throw error;
    }
  }

  // Новый метод для проверки аутентификации
  const fetchUser = async(): Promise<User | null> => {
    try {
      if (!token.value) return null;

      const { data } = await http.get('/profile');
      user.value = data;
      return data;
    } catch (error) {
      token.value = null;
      user.value = null;
      localStorage.removeItem('auth_token');
      throw error;
    }
  }

  const logout =async ()=> {
    try {
      const {data} = await http.post('/logout' );
      token.value = null;
      user.value = null;
      localStorage.removeItem('auth_token');
      return data;
    } catch (error) {
      console.error('Login failed:', error.response?.data || error.message);
      throw error;
    }
  }

  return {
    user,
    token,
    createUser,
    login,
    fetchUser,
    logout
  };
});
