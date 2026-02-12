<script setup>
import { onMounted, ref } from 'vue'
import api from '../services/api'

const pegawai = ref([])
const form = ref({
  nama: '',
  email: '',
  no_hp: '',
  alamat: '',
})

const loadPegawai = async () => {
  const response = await api.get('/pegawai')
  pegawai.value = response.data
}

const simpanPegawai = async () => {
  try {
    if (isEdit.value) {
      await api.put(`/pegawai/${editId.value}`, form.value)
      isEdit.value = false
      editId.value = null
    } else {
      await api.post('/pegawai', form.value)
    }

    form.value = {
      nama: '',
      email: '',
      no_hp: '',
      alamat: '',
    }

    loadPegawai()
  } catch (error) {
    console.error(error)
    alert('Terjadi kesalahan!')
  }
}

const isEdit = ref(false)
const editId = ref(null)

const editPegawai = (p) => {
  form.value = { ...p }
  isEdit.value = true
  editId.value = p.id
}

const hapusPegawai = async (id) => {
  if (confirm('Yakin hapus pegawai ini?')) {
    await api.delete(`/pegawai/${id}`)
    loadPegawai()
  }
}

const batalEdit = () => {
  isEdit.value = false
  editId.value = null

  form.value = {
    nama: '',
    email: '',
    no_hp: '',
    alamat: '',
  }
}

onMounted(() => {
  loadPegawai()
})
</script>

<template>
  <div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-xl p-6">
      <h1 class="text-2xl font-bold mb-6">Manajemen Pegawai</h1>

      <!-- FORM -->
      <div class="grid grid-cols-4 gap-4 mb-6">
        <input
          v-model="form.nama"
          placeholder="Nama"
          class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <input
          v-model="form.email"
          placeholder="Email"
          class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <input
          v-model="form.no_hp"
          placeholder="No HP"
          class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
        <input
          v-model="form.alamat"
          placeholder="Alamat"
          class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        />
      </div>

      <div class="flex items-center gap-3 mb-6">
        <button
          @click="simpanPegawai"
          class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition"
        >
          {{ isEdit ? 'Update Pegawai' : 'Tambah Pegawai' }}
        </button>

        <button
          v-if="isEdit"
          @click="batalEdit"
          class="bg-gray-400 text-white px-5 py-2 rounded-lg hover:bg-gray-500 transition"
        >
          Batal
        </button>
      </div>

      <!-- TABLE -->
      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left">ID</th>
              <th class="px-4 py-2 text-left">Nama</th>
              <th class="px-4 py-2 text-left">Email</th>
              <th class="px-4 py-2 text-left">No HP</th>
              <th class="px-4 py-2 text-left">Alamat</th>
              <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in pegawai" :key="p.id" class="border-t hover:bg-gray-50">
              <td class="px-4 py-2">{{ p.id }}</td>
              <td class="px-4 py-2 font-medium">{{ p.nama }}</td>
              <td class="px-4 py-2">{{ p.email }}</td>
              <td class="px-4 py-2">{{ p.no_hp }}</td>
              <td class="px-4 py-2">{{ p.alamat }}</td>
              <td class="px-4 py-2 space-x-2">
                <button
                  @click="editPegawai(p)"
                  class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500"
                >
                  Edit
                </button>

                <button
                  @click="hapusPegawai(p.id)"
                  class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                >
                  Hapus
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
