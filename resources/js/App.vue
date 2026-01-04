<template>
    <Navbar v-if="authStore.isAuthenticated" />
    <router-view></router-view>
</template>

<script setup>
import Navbar from './components/Navbar.vue';
import { useAuthStore } from './store/auth';
const authStore = useAuthStore();
</script>