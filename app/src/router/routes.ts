import {RouteRecordRaw} from 'vue-router';

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    component: () => import('layouts/UserLayout.vue'),
    children: [
      {
        path: '', component: () => import('pages/Dashboard.vue'),
        name: 'dashboard', meta: {requiresAuth: true}
      },
      {
        path: '/tests', component: () => import('pages/test/List.vue'),
        name: 'test.list', meta: {requiresAuth: true}
      },
      {
        path: '/test/:id', component: () => import('pages/test/Show.vue'),
        name: 'test.start', meta: {requiresAuth: true}
      },
      {
        path: '/test/:id/completed', component: () => import('pages/test/Complete.vue'),
        name: 'test.complete', meta: {requiresAuth: true}
      },
      {
        path: '/test/:id/results', component: () => import('pages/test/Result.vue'),
        name: 'test.results', meta: {requiresAuth: true}
      }
    ],
  },
  {
    path: '/login', component: () => import('pages/auth/Login.vue'),
    name: 'login'
  },
  {
    path: '/register', component: () => import('pages/auth/Register.vue'),
    name: 'register'
  },

  // The catch-all route to display a 404 Not Found page if the user navigates to a non-existent page
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/error/404.vue')
  }
];

export default routes;
