import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/store/auth';



const router = createRouter({
  history: createWebHistory(),
  routes: [

    {
      path: '/',
      redirect: '/dashboard'
    },
    {
      path: '/login',
      redirect: { name: 'login' } // Перенаправляем на именованный маршрут
    },

    {
      path: '/auth',
      component: () => import('@/layouts/AuthLayout.vue'),
      meta: { guest: true },
      children: [
        {
          path: 'login',
          name: 'login',
          component: () => import('@/views/auth/Login.vue')
        },
 
        {
          path: 'register',
          name: 'register',
          component: () => import('@/views/auth/Register.vue')
        }
      ]
    },

    {
      path: '/dashboard',
      component: () => import('@/layouts/AppLayout.vue'),
      meta: { requiresAuth: true },
      children: [

        {
          path: '',
          name: 'dashboard',
          component: () => import('@/views/dashboard/Index.vue')
        },
        {
          path: 'tiptap',
          name: 'tiptap',
          component: () => import('@/components/Tiptap/Index.vue')
        },
        
       {
         path: 'catalog-index',
         name: 'catalog-index',
         component: () => import('@/components/Catalog/Index/Index.vue')
       },
       {
         path: 'catalog-create',
         name: 'catalog-create',
         component: () => import('@/components/Catalog/Create/Index.vue'),
         props: {
          isEdit:false
         }
       },
       {
         path: 'catalog/edit/:catatog_id',
         name: 'catalog-edit',
         component: () => import('@/components/Catalog/Create/Index.vue'),
         props: {
          isEdit:true
         }
       },
       {
         path: 'catalog/test',
         name: 'catalog-test',
         component: () => import('@/components/Catalog/Create/Test.vue'),
          
       },
       {
         path: 'article-index',
         name: 'article-index',
         component: () => import('@/components/Article/Index/Index.vue')
       },
       {
         path: 'article-create',
         name: 'article-create',
         component: () => import('@/components/Article/Create/Index.vue'),
         props: {
          isEdit:false
         }
       },
       {
         path: 'article/edit/:article_id',
         name: 'article-edit',
         component: () => import('@/components/Article/Create/Index.vue'),
         props: {
          isEdit:true
         }
       },
      ]
    }
     
  ]
});

router.beforeEach(async (to, from, next) => {
  console.log('aaaaa');
  console.log('Trying to navigate to:', to.path, 'matched:', to.matched)
  const authStore = useAuthStore();

  // Инициализируем состояние аутентификации
  ///const isAuthenticated = authStore.token  !== null;

  // Если маршрут требует аутентификации
  if (to.meta.requiresAuth) {
    console.log("начинаем авторизацию")
    try {

      // Если есть токен, но нет данных пользователя - загружаем их
      if (authStore.token  && !authStore.user ) {
        console.log("ЕСТЬ ТОКЕН но нет юзера")
          await authStore.fetchUser();
      }

      // Проверяем аутентификацию
      if (authStore.token && authStore.user) {

        next();
        return; // Важно: завершаем выполнение
      } else {
        // Если аутентификация не прошла
        authStore.logout(); // Очищаем невалидные данные
        next({ name: 'login', query: { redirect: to.fullPath } });
      }
    } catch (error) {
      console.error('Auth check failed:', error);
      authStore.logout(); // Очищаем данные при ошибке
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
