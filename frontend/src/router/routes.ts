export default [
 
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
 