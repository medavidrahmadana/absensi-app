<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

const pegawaiList = ref([])
const form = ref({
  pegawai_id: '',
  tanggal: '',
  status: 'hadir',
})

const loadPegawai = async () => {
  const res = await api.get('/pegawai')
  pegawaiList.value = res.data
}

const simpanKehadiran = async () => {
  try {
    await api.post('/kehadiran', form.value)

    form.value = {
      pegawai_id: '',
      tanggal: '',
      status: 'hadir',
    }

    alert('Kehadiran berhasil disimpan!')
  } catch (error) {
    console.error(error)
    alert('Terjadi kesalahan!')
  }
}

onMounted(() => {
  loadPegawai()
})
</script>

<template>
  <div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-2xl p-6">
      <h1 class="text-2xl font-bold mb-6">Input Kehadiran</h1>

      <div class="space-y-4">
        <!-- Pilih Pegawai -->
        <select v-model="form.pegawai_id" class="w-full border rounded-lg px-3 py-2">
          <option value="">Pilih Pegawai</option>
          <option v-for="p in pegawaiList" :key="p.id" :value="p.id">
            {{ p.nama }}
          </option>
        </select>

        <!-- Tanggal -->
        <input type="date" v-model="form.tanggal" class="w-full border rounded-lg px-3 py-2" />

        <!-- Status -->
        <select v-model="form.status" class="w-full border rounded-lg px-3 py-2">
          <option value="hadir">Hadir</option>
          <option value="izin">Izin</option>
          <option value="sakit">Sakit</option>
        </select>

        <button
          @click="simpanKehadiran"
          class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition"
        >
          Simpan Kehadiran
        </button>
      </div>
    </div>
  </div>
</template>
