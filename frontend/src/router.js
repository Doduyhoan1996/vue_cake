import Vue from 'vue'
import Router from 'vue-router'
import store from './store'

Vue.use(Router)

let router = new Router({
  mode: 'abstract',
  base: process.env.BASE_URL,
  routes: [{
      name: 'login',
      path: '/login',
      component: () => import('./views/app/Login.vue'),
      meta: {
        rule: 'isPublic'
      }
    },
    {
      path: '/error-permission',
      name: 'error-permission',
      component: () => import('./views/error/403.vue'),
      meta: {
        rule: '*'
      }
    },
    {
      path: '/',
      component: () => import('./views/Layout.vue'),
      meta: {
        requiresAuth: true
      },
      children: [{
          path: '/',
          name: 'dashboard',
          component: () => import('./views/app/Dashboard.vue'),
          meta: {
						rule: 'isLogged'
					}
        },
        {
          path: 'timesheet',
          name: 'timesheet',
          component: () => import('./views/timesheet/Index.vue'),
          meta: {
						rule: 'isLogged'
					}
        },
        {
          path: 'users',
          name: 'users',
          component: () => import('./views/user/Index.vue'),
          meta: {
						rule: 'isAdmin'
					}
        },
        {
          path: 'calendar',
          name: 'calendar',
          component: () => import('./views/calendar/Index.vue'),
          meta: {
						rule: 'isAdmin'
					}
        }
      ]
    }
  ]
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    let user_info = store.state.app_user;
    if (user_info) {
      return next();
    }
    next({
      name: 'login'
    });
  } else {
    next();
  }
});

export default router;