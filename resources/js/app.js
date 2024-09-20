import { createApp } from 'vue';
import App from './components/App.vue';
import axios from 'axios';

axios.defaults.baseURL = '/';

createApp(App).mount('#app');
