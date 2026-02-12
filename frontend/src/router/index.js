import { createRouter, createWebHistory } from 'vue-router'
import PegawaiView from '../views/PegawaiView.vue'
import KehadiranView from '../views/KehadiranView.vue'
import LaporanView from '../views/LaporanView.vue'

const routes = [
  {
    path: '/',
    name: 'pegawai',
    component: PegawaiView,
  },
  {
    path: '/kehadiran',
    name: 'kehadiran',
    component: KehadiranView,
  },
  {
    path: '/laporan',
    name: 'laporan',
    component: LaporanView,
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router
