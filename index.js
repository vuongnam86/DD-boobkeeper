import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../store/auth';
import Login from '../components/Login.vue';
import Dashboard from '../components/Dashboard.vue';
import EmployeeList from '../components/EmployeeList.vue';
import EmployeeCreate from '../components/EmployeeCreate.vue';
import EmployeeEdit from '../components/EmployeeEdit.vue';
import Payroll from '../components/Payroll.vue';

const routes = [
    { path: '/', redirect: '/dashboard' },
    { path: '/login', component: Login, name: 'login' },
    { path: '/dashboard', component: Dashboard, name: 'dashboard', meta: { requiresAuth: true } },
    { path: '/employees', component: EmployeeList, name: 'employees', meta: { requiresAuth: true } },
    { path: '/employees/create', component: EmployeeCreate, name: 'employees.create', meta: { requiresAuth: true } },
    { path: '/employees/:id/edit', component: EmployeeEdit, name: 'employees.edit', meta: { requiresAuth: true } },
    { path: '/payroll', component: Payroll, name: 'payroll', meta: { requiresAuth: true } },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        // If route requires auth and user is not authenticated, redirect to login
        next({ name: 'login' });
    } else if (to.name === 'login' && authStore.isAuthenticated) {
        // If user is authenticated and tries to visit login, redirect to dashboard
        next({ name: 'dashboard' });
    } else {
        next();
    }
});

export default router;