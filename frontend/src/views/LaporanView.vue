<script setup>
import { onMounted, ref } from 'vue'
import api from '../services/api'

const pegawaiId = ref('')
const pegawaiList = ref([])
const periode = ref('')
const laporan = ref(null)

const today = new Date()
const maxMonth = today.toISOString().slice(0, 7)

onMounted(() => {
  periode.value = maxMonth
  loadPegawai()
})

const loadPegawai = async () => {
  const response = await api.get('/pegawai')
  pegawaiList.value = response.data
}

const tampilkanLaporan = async () => {
  try {
    if (!pegawaiId.value || !periode.value) {
      alert('Lengkapi data dulu')
      return
    }

    const [year, month] = periode.value.split('-')

    const response = await api.get(`/kehadiran/${pegawaiId.value}/${year}/${month}`)

    laporan.value = response.data
  } catch (error) {
    console.error(error)
    alert('Gagal memuat laporan')
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-2xl p-6">
      <h1 class="text-2xl font-bold mb-6">Laporan Bulanan</h1>

      <div class="space-y-4 mb-6">
        <select v-model="pegawaiId" class="w-full border rounded-lg px-3 py-2">
          <option value="">Pilih Pegawai</option>
          <option v-for="p in pegawaiList" :key="p.id" :value="p.id">
            {{ p.nama }}
          </option>
        </select>

        <input
          type="month"
          v-model="periode"
          :max="maxMonth"
          class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />

        <button
          @click="tampilkanLaporan"
          class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700"
        >
          Tampilkan Laporan
        </button>
      </div>

      <div v-if="laporan" class="bg-gray-50 p-4 rounded-lg mt-4">
        <p class="font-semibold">
          Total Hari Kerja Bulan Ini:
          {{ laporan.total_hari_kerja_bulan }}
          (Hari Kerja Aktif: {{ laporan.hari_kerja_aktif }} Hari)
        </p>

        <p>Hadir: {{ laporan.hadir }}</p>
        <p>Izin: {{ laporan.izin }}</p>
        <p>Sakit: {{ laporan.sakit }}</p>
        <p>Alpha: {{ laporan.alpha }}</p>
      </div>
    </div>
  </div>
</template>
