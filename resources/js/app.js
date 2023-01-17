require('./bootstrap');
import { createApp } from 'vue'
import App from './component/App.vue'
import Router from './router'
import ToastPlugin from 'vue-toast-notification'
import 'vue-toast-notification/dist/theme-sugar.css';

const app = createApp(App);
app.use(Router);
app.use(ToastPlugin);

app.mount('#app');

