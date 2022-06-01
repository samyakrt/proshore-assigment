import * as vue from 'vue'
import vueRouter from './vue/router/index'
import axios from 'axios';
import Toast from "vue-toastification";

axios.defaults.headers = {
    ...axios.defaults.headers,
    'Content-Type': 'application/json',
    'Accept': 'application/json',
}
Window.Vue = vue;

const app = vue.createApp({})
app.use(vueRouter);
app.use(Toast)

app.mount('#app');