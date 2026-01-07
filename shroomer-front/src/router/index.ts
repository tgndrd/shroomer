import { createRouter, createWebHistory } from 'vue-router'
import ViewHome from '@/views/ViewHome.vue'
import ViewZone from '@/views/ViewZone.vue'
import ViewRegister from "@/views/ViewRegister.vue";
import ViewLogin from "@/views/ViewLogin.vue";
import authService from "@/services/auth.service.ts";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/shroomer',
      name: 'shroomer',
      component: ViewHome,
    },
    {
      path: '/zone/:id',
      name: 'zone',
      component: ViewZone,
    },
    {
      path: '/login',
      name: 'login',
      component: ViewLogin,
    },
    {
      path: '/register',
      name: 'register',
      component: ViewRegister,
    },
  ],
})

router.beforeEach(async (to) => {
  const publicPages : string[] = [
    '/shroomer',
    '/login',
    '/register',
  ]

  if (!publicPages.includes(to.path) && !authService.authenticated()) {
    return '/login'
  }
})

export default router
