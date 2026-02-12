<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-md px-8 py-4 flex gap-6">
      <router-link to="/" class="font-semibold text-gray-700 hover:text-blue-600">
        Pegawai
      </router-link>

      <router-link to="/kehadiran" class="font-semibold text-gray-700 hover:text-blue-600">
        Kehadiran
      </router-link>

      <router-link to="/laporan" class="font-semibold text-gray-700 hover:text-blue-600">
        Laporan
      </router-link>
    </nav>

    <router-view />
  </div>
</template>
