<template>
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Daily Sales Data</h1>

        <!-- Upload Section -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Import Sales Data (CSV)</h2>
            <form @submit.prevent="uploadFile" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-1/2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
                    <input type="file" ref="fileInput" accept=".csv" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100" required>
                </div>
                <button type="submit" :disabled="uploading" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition disabled:opacity-50">
                    {{ uploading ? 'Uploading...' : 'Upload CSV' }}
                </button>
            </form>
            <p v-if="message" :class="{'text-green-600': success, 'text-red-600': !success}" class="mt-4 text-sm">
                {{ message }}
            </p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="font-medium text-gray-900">CSV Format Guide</h3>
            <p class="text-sm text-gray-500 mt-2">
                Your CSV file should have the following headers: <br>
                <code class="bg-gray-100 px-1">date, employee_name, service_total, tips, hours_worked</code>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const fileInput = ref(null);
const uploading = ref(false);
const message = ref('');
const success = ref(false);

const uploadFile = async () => {
    const file = fileInput.value.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);

    uploading.value = true;
    message.value = '';

    try {
        await axios.post('/api/daily-sales/import', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        message.value = 'File uploaded successfully!';
        success.value = true;
        fileInput.value.value = '';
    } catch (error) {
        console.error(error);
        message.value = 'Failed to upload file. ' + (error.response?.data?.message || 'Server Error');
        success.value = false;
    } finally {
        uploading.value = false;
    }
};
</script>