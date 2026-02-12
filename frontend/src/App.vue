<script setup>
import { onMounted, ref } from 'vue'
import api from './services/api'

const pegawai = ref([])

onMounted(async () => {
  try {
    const response = await api.get('/pegawai')
    pegawai.value = response.data
  } catch (error) {
    console.error(error)
  }
})
</script>

<template>
  <div>
    <h1>Data Pegawai</h1>
    <ul>
      <li v-for="p in pegawai" :key="p.id">
        {{ p.nama }}
      </li>
    </ul>
  </div>
</template>
