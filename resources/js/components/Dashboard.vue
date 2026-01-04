<template>
    <div class="container p-4 mx-auto">
        <h1 class="text-3xl font-bold">Dashboard</h1>
        <p class="mt-4">Welcome! You are logged in.</p>

        <button @click="handleLogout" class="px-4 py-2 mt-6 font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
            Logout
        </button>
    </div>
</template>

<script setup>
import { useAuthStore } from '../store/auth';
const authStore = useAuthStore();
const handleLogout = () => authStore.logout();
</script>