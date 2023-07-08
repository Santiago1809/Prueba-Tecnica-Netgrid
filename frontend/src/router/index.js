import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import TareasView from '@/views/TareasView.vue'
const routes = [
  {
    path: '/index',
    name: 'home',
    component: HomeView
  },
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/home',
    name: 'about',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  },
  {
    path: '/tasks/:id',
    name: 'tareas',
    component: TareasView
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
