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
         path: 'catalogs/index',
         name: 'catalogs-index',
         component: () => import('@/components/Catalog/Index/Index.vue'),
         meta: {
          //requiresAuth: true,
          //middlewares: [auth],
          allowedRoles: "Admin",
        },
       },
       {
         path: 'catalogs/create',
         name: 'catalogs-create',
         component: () => import('@/components/Catalog/Create/Index.vue'),
         props: {
          isEdit:false
         },
         meta: {
          //requiresAuth: true,
          //middlewares: [auth],
          allowedRoles: "Admin",
        },
       },
       {
         path: 'catalogs/edit/:catatog_id',
         name: 'catalogs-edit',
         component: () => import('@/components/Catalog/Create/Index.vue'),
         props: {
          isEdit:true
         },
         meta: {
          //requiresAuth: true,
          //middlewares: [auth],
          allowedRoles: "Admin",
        },
       },
       
       {
         path: 'articles/index',
         name: 'articles-index',
         component: () => import('@/components/Article/Index/Index.vue'),
         meta: {
          //requiresAuth: true,
          //middlewares: [auth],
          allowedRoles: "Admin",
        },
       },
       {
         path: 'articles/create',
         name: 'articles-create',
         component: () => import('@/components/Article/Create/Index.vue'),
         props: {
          isEdit:false
         },
         meta: {
          //requiresAuth: true,
          //middlewares: [auth],
          allowedRoles: "Admin",
        },
       },
       {
         path: 'articles/edit/:article_id',
         name: 'articles-edit',
         component: () => import('@/components/Article/Create/Index.vue'),
         props: {
          isEdit:true
         },
         meta: {
          //requiresAuth: true,
          //middlewares: [auth],
          allowedRoles: "Admin",
        },
       },

        {
         path: 'subjects/index',
         name: 'subjects-index',
         component: () => import('@/components/Subject/Index/Index.vue'),
         props: {
          isEdit:false
         },
         meta: {
          //requiresAuth: true,
          //middlewares: [auth],
          allowedRoles: "User",
        },
       },

        {
         path: 'subjects/create',
         name: 'subjects-create',
         component: () => import('@/components/Subject/Create/Index.vue'),
         props: {
          isEdit:false
         },
         meta: {
          //requiresAuth: true,
          //middlewares: [auth],
          allowedRoles: "User",
        },
       },
       {
         path: 'subjects/edit/:subject_id',
         name: 'asubject-edit',
         component: () => import('@/components/Subject/Create/Index.vue'),
         props: {
          isEdit:true
         },
         meta: {
          //requiresAuth: true,
          //middlewares: [auth],
          allowedRoles: "User",
        },
       },

      {
        name: "forbidden",
        path: "/forbidden",
        component: () => import("@/views/error/Forbidden.vue"),
      },

      ]
    }
     
  ]
 