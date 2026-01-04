<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
            <h1 class="text-2xl font-bold text-center">Login</h1>
            <form @submit.prevent="handleLogin" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="email" type="email" id="email" required
                           class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input v-model="password" type="password" id="password" required
                           class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <p v-if="errorMessage" class="text-sm text-red-600">{{ errorMessage }}</p>
                <div>
                    <button type="submit"
                            class="w-full px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../store/auth';

const email = ref('admin@example.com');
const password = ref('password');
const errorMessage = ref('');
const authStore = useAuthStore();
const router = useRouter();

const handleLogin = async () => {
    const success = await authStore.login({ email: email.value, password: password.value });
    if (success) {
        router.push('/dashboard');
    } else {
        errorMessage.value = 'Invalid credentials. Please try again.';
    }
};
</script>