import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/store/auth';
import routes from "@/router/routes";
 
 


const router = createRouter({
  history: createWebHistory(),
  routes,
});
 

router.beforeEach(async (to, from, next) => {
   
  const authStore = useAuthStore();

  // Инициализируем состояние аутентификации
  ///const isAuthenticated = authStore.token  !== null;

  // Если маршрут требует аутентификации
  if (to.meta.requiresAuth) {
    
    try {

      // Если есть токен, но нет данных пользователя - загружаем их
      if (authStore.token  && !authStore.user ) {
         
          await authStore.fetchUser();
      }

      // Проверяем аутентификацию
      if (authStore.token && authStore.user) {


        if (to.meta.allowedRoles && !authStore.hasRole (to.meta.allowedRoles as string) ) {
           next({ name: "forbidden" });
          }



        next();
        return; // Важно: завершаем выполнение
      } else {
        // Если аутентификация не прошла
        authStore.logout(); // Очищаем невалидные данные
        next({ name: 'login', query: { redirect: to.fullPath } });
      }
    } catch (error) {
      
      authStore.logout(); // Очищаем данные при ошибке
      console.log(error)
      next({ name: 'login' });
    }
  }
  // Если маршрут только для гостей
  if (to.meta.guest) {
    if (authStore.token) {
      next({ name: 'dashboard' });
      return;
    }
    next();
    return;
  }
  // Для всех остальных маршрутов
  else {
    next();
  }
});

export default router;
