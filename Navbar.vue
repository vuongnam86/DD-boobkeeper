<template>
    <nav class="bg-white border-b border-gray-200 shadow-sm mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="font-bold text-xl text-blue-600">DD Bookkeeper</span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <router-link to="/dashboard" active-class="border-blue-500 text-gray-900" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Dashboard
                        </router-link>
                        <router-link to="/employees" active-class="border-blue-500 text-gray-900" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Employees
                        </router-link>
                        <router-link to="/payroll" active-class="border-blue-500 text-gray-900" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Payroll
                        </router-link>
                    </div>
                </div>
                <div class="flex items-center">
                    <button @click="handleLogout" class="text-sm text-red-600 hover:text-red-900 font-medium">Logout</button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { useAuthStore } from '../store/auth';

const authStore = useAuthStore();
const handleLogout = () => {
    authStore.logout();
};
</script>