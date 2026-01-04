<template>
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Employees</h1>
            <button @click="$router.push('/employees/create')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Add Employee
            </button>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="employee in employees" :key="employee.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ employee.first_name }} {{ employee.last_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ employee.email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span :class="employee.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                {{ employee.status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="$router.push(`/employees/${employee.id}/edit`)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                            <button @click="deleteEmployee(employee.id)" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="employees.length === 0">
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No employees found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const employees = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/api/employees');
        employees.value = response.data;
    } catch (error) {
        console.error('Error fetching employees:', error);
    }
});

const deleteEmployee = async (id) => {
    if (!confirm('Are you sure you want to delete this employee?')) return;
    try {
        await axios.delete(`/api/employees/${id}`);
        employees.value = employees.value.filter(e => e.id !== id);
    } catch (error) {
        alert('Failed to delete employee.');
    }
};
</script>