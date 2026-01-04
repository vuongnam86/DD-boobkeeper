<template>
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Payroll Calculation</h1>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input v-model="startDate" type="date" class="w-full rounded-md border-gray-300 shadow-sm border p-2">
                </div>
                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input v-model="endDate" type="date" class="w-full rounded-md border-gray-300 shadow-sm border p-2">
                </div>
                <div class="w-full md:w-auto">
                    <button @click="calculatePayroll" class="w-full bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                        Calculate
                    </button>
                </div>
            </div>
        </div>

        <div v-if="results.length > 0" class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Sales</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Gross Wage</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Net Tips</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Pay</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(row, index) in results" :key="index">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ row.name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${{ row.total_sales }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${{ row.gross_wage }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${{ row.net_tips }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">${{ row.total_pay }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else-if="hasSearched" class="text-center text-gray-500 mt-8">
            No payroll data found for this period.
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const startDate = ref('2024-01-01');
const endDate = ref('2024-01-31');
const results = ref([]);
const hasSearched = ref(false);

const calculatePayroll = async () => {
    try {
        const response = await axios.get('/api/payroll/calculate', {
            params: {
                start_date: startDate.value,
                end_date: endDate.value
            }
        });
        results.value = response.data;
        hasSearched.value = true;
    } catch (error) {
        console.error('Error calculating payroll:', error);
        alert('Failed to calculate payroll.');
    }
};
</script>