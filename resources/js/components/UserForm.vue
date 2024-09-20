<template>
    <div>
        <h2 class="text-xl font-bold mb-4">Add New User</h2>
        <form @submit.prevent="submitForm" class="max-w-md">
            <div class="mb-4">
                <label class="block text-gray-700">Name</label>
                <input v-model="form.name" type="text" class="w-full px-3 py-2 border rounded" :class="{'border-red-500': errors.name}" >
                <p v-if="errors.name" class="text-red-500 text-sm">{{ errors.name[0] }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input v-model="form.email" type="text" class="w-full px-3 py-2 border rounded" :class="{'border-red-500': errors.email}" >
                <p v-if="errors.email" class="text-red-500 text-sm">{{ errors.email[0] }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Phone</label>
                <input v-model="form.phone" type="text" class="w-full px-3 py-2 border rounded" placeholder="+380" :class="{'border-red-500': errors.phone}" >
                <p v-if="errors.phone" class="text-red-500 text-sm">{{ errors.phone[0] }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Position</label>
                <select v-model="form.position_id" class="w-full px-3 py-2 border rounded" :class="{'border-red-500': errors.position_id}" >
                    <option v-for="position in positions" :key="position.id" :value="position.id">{{ position.name }}</option>
                </select>
                <p v-if="errors.position_id" class="text-red-500 text-sm">{{ errors.position_id[0] }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Photo</label>
                <input @change="handleFileUpload" type="file" class="w-full" :class="{'border-red-500': errors.photo}" >
                <p v-if="errors.photo" class="text-red-500 text-sm">{{ errors.photo[0] }}</p>
            </div>

            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Submit</button>
        </form>

        <div v-if="message" class="mt-4">
            <p :class="{'text-green-500': success, 'text-red-500': !success}">{{ message }}</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'UserForm',
    data() {
        return {
            form: {
                name: '',
                email: '',
                phone: '',
                position_id: '',
                photo: null,
            },
            positions: [],
            message: '',
            success: false,
            errors: {}, // Додаємо об'єкт для зберігання помилок
        };
    },
    methods: {
        async fetchPositions() {
            try {
                const response = await axios.get('/api/positions');
                if (response.data.success) {
                    this.positions = response.data.positions;
                }
            } catch (error) {
                console.error('Error fetching positions:', error);
            }
        },
        handleFileUpload(event) {
            this.form.photo = event.target.files[0];
        },
        async submitForm() {
            try {
                // Отримання токена
                const tokenResponse = await axios.get('/api/token');
                const token = tokenResponse.data.token;

                // Підготовка даних форми
                let formData = new FormData();
                formData.append('name', this.form.name);
                formData.append('email', this.form.email);
                formData.append('phone', this.form.phone);
                formData.append('position_id', this.form.position_id);
                formData.append('photo', this.form.photo);

                // Відправлення запиту на сервер
                const response = await axios.post('/api/users', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `Bearer ${token}`,
                    },
                });

                if (response.data.success) {
                    this.message = response.data.message;
                    this.success = true;
                    this.$emit('user-added');
                    // Очистка форми
                    this.form = {
                        name: '',
                        email: '',
                        phone: '',
                        position_id: '',
                        photo: null,
                    };
                    this.errors = {}; // Очищаємо помилки
                }
            } catch (error) {
                this.success = false;
                if (error.response && error.response.data) {
                    this.message = error.response.data.message;
                    this.errors = error.response.data.fails || {}; // Відображаємо помилки
                } else {
                    this.message = 'An error occurred.';
                    console.error('Error:', error);
                }
            }
        },
    },
    mounted() {
        this.fetchPositions();
    },
};
</script>

<style>
/* Додаткові стилі */
</style>
