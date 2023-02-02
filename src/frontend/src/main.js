import { createApp } from 'vue';
import App from './App.vue';
import VueApexCharts from "vue3-apexcharts";
// import * as Vue from 'vue' // in Vue 3
import axios from 'axios';
import VueAxios from 'vue-axios';
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";

import './assets/main.css';

const app = createApp(App);
app.use(VueApexCharts);
app.use(VueAxios, axios);
app.mount('#app');
