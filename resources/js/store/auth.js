import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token') || null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {
        async login(credentials) {
            try {
                const response = await axios.post('/api/login', credentials);
                const { token, user } = response.data;

                // Store token and user data
                this.token = token;
                this.user = user;
                localStorage.setItem('token', token);

                // Set axios default header for subsequent requests
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                return true;
            } catch (error) {
                console.error('Login failed:', error);
                return false;
            }
        },

        logout() {
            // Clear state and local storage
            this.token = null;
            this.user = null;
            localStorage.removeItem('token');

            // Remove axios header
            delete axios.defaults.headers.common['Authorization'];

            // Redirect to login
            this.router.push('/login');
        },
    },
});