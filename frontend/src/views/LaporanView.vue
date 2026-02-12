<script setup>
import { ref } from 'vue'
import api from '../services/api'

const pegawaiList = ref([])
const pegawaiId = ref('')
const bulan = ref('')
const tahun = ref('')
const laporan = ref(null)

const loadPegawai = async () => {
  const res = await api.get('/pegawai')
  pegawaiList.value = res.data
}

const getLaporan = async () => {
  try {
    const res = await api.get(`/kehadiran/${pegawaiId.value}/${tahun.value}/${bulan.value}`)
    laporan.value = res.data
  } catch (error) {
    console.error(error)
    alert('Gagal mengambil laporan')
  }
}

loadPegawai()
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
          type="number"
          v-model="tahun"
          placeholder="Tahun (contoh: 2026)"
          class="w-full border rounded-lg px-3 py-2"
        />

        <input
          type="number"
          v-model="bulan"
          placeholder="Bulan (1-12)"
          class="w-full border rounded-lg px-3 py-2"
        />

        <button
          @click="getLaporan"
          class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700"
        >
          Tampilkan Laporan
        </button>
      </div>

      <div v-if="laporan" class="bg-gray-50 p-4 rounded-lg">
        <p><strong>Total Hari Kerja:</strong> {{ laporan.total_hari_kerja }}</p>
        <p><strong>Hadir:</strong> {{ laporan.hadir }}</p>
        <p><strong>Izin:</strong> {{ laporan.izin }}</p>
        <p><strong>Sakit:</strong> {{ laporan.sakit }}</p>
        <p><strong>Alpha:</strong> {{ laporan.alpha }}</p>
      </div>
    </div>
  </div>
</template>
