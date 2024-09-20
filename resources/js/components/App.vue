<template>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4 text-center">User Management</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Users List</h2>
                <div v-if="users.length">
                    <ul>
                        <li v-for="user in users" :key="user.id" class="border-b py-2">
                            <div class="flex items-center">
                                <img :src="user.photo" alt="User Photo" class="w-10 h-10 rounded-full mr-4">
                                <div>
                                    <p class="font-semibold">{{ user.name }}</p>
                                    <p class="text-sm text-gray-600">{{ user.email }}</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <button @click="loadMore" v-if="nextUrl" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">
                        Show More
                    </button>
                </div>
                <div v-else>
                    <p>No users found.</p>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-md">
                <UserForm @user-added="reloadUsers"></UserForm>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import UserForm from './UserForm.vue';

export default {
    name: 'App',
    components: {
        UserForm,
    },
    data() {
        return {
            users: [],
            nextUrl: null,
        };
    },
    methods: {
        async fetchUsers(url = '/api/users?count=6') {
            try {
                const response = await axios.get(url);
                if (response.data.success) {
                    this.users = [...this.users, ...response.data.users];
                    this.nextUrl = response.data.links.next_url;
                }
            } catch (error) {
                console.error('Error fetching users:', error);
            }
        },
        loadMore() {
            if (this.nextUrl) {
                this.fetchUsers(this.nextUrl);
            }
        },
        reloadUsers() {
            this.users = [];
            this.fetchUsers();
        },
    },
    mounted() {
        this.fetchUsers();
    },
};
</script>

<style scoped>
/* Додаткові стилі, якщо необхідно */
</style>
