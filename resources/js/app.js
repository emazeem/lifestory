import { createApp } from 'vue/dist/vue.esm-bundler.js';
import App from './App.vue';
import router from './router';

import axios from 'axios'
import VueAxios from 'vue-axios'

import VCalendar from 'v-calendar';
import 'v-calendar/style.css';

import moshaToast from 'mosha-vue-toastify'
import 'mosha-vue-toastify/dist/style.css'

import moment from 'moment';

const app = createApp(App);

app.use(router);
app.use(VueAxios, axios);
app.use(VCalendar, {});
app.use(moshaToast);


const token = window.localStorage.getItem('sanctum_token');
const expirationDate = localStorage.getItem('expiration_date');
if (token && expirationDate) {
    const currentDate = new Date().getTime();
    if (currentDate > expirationDate) {
        localStorage.removeItem('sanctum_token');
        localStorage.removeItem('expiration_date');
        window.location.href = "/";
    }
}

var baseUrl = window.location.origin;
axios.defaults.baseURL = baseUrl + '/api/';

axios.interceptors.request.use(function (config) {
    let tokenTwo = window.localStorage.getItem('sanctum_token');
    if (tokenTwo) {
        config.headers['Authorization'] = 'Bearer ' + tokenTwo;
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});

app.config.globalProperties.$flattenErrors = function (error) {
    const flattenedErrors = {};
    if (error.response.status === 422) {
        for (const field in error.response.data.message) {
            if (Array.isArray(error.response.data.message[field])) {
                flattenedErrors[field] = error.response.data.message[field][0];
            }
        }
    }
    else if (error.response.status === 421) {
        flattenedErrors['message'] = error.response.data.message;
    }
    return flattenedErrors;
}

app.config.globalProperties.$assetUrl = function (url) {
    return baseUrl + "/" + url;
}

app.config.globalProperties.$formatDateAsAmPm = function (date) {
    // Convert the date from Laravel to a moment.js object
    const dateObject = moment(date, 'YYYY-MM-DD HH:mm:ss');

    // Format the date in the desired format
    return dateObject.format('MM-DD-YYYY, h:mm A');
}

app.config.globalProperties.$date = function (date) {
    // Convert the date from Laravel to a moment.js object
    const dateObject = moment(date, 'YYYY-MM-DD HH:mm:ss');

    // Format the date in the desired format
    return dateObject.format('MM-DD-YYYY');
}

app.config.globalProperties.$checkObject = function (data) {
    return Object.keys(data).length > 0 ? true : false;
}

app.config.globalProperties.$changeFont = function (value) {
    if (value == 'small' || value == 'medium' || value == 'large') {

        const classesToAddFontSizes = ['.ct-card-header', '.ct-form-label', '.ct-form-control', '.ct-form-control-icon', '.ct-p', '.ct-pl'];

        classesToAddFontSizes.forEach((className) => {
            const elements = document.querySelectorAll(className);
            elements.forEach((element) => {
                element.classList.remove('small');
                element.classList.remove('medium');
                element.classList.remove('large');
                element.classList.add(value);
            });
        });
        localStorage.setItem("font_size_class", value);
    }
}

app.config.globalProperties.$updateFont = function () {
    const font_size = localStorage.getItem('font_size_class');
    if (font_size == 'small' || font_size == 'medium' || font_size == 'large') {
        const classesToAddFontSizes = ['.ct-card-header', '.ct-form-label', '.ct-form-control', '.ct-form-control-icon', '.ct-p', '.ct-pl'];
        classesToAddFontSizes.forEach((className) => {
            const elements = document.querySelectorAll(className);
            elements.forEach((element) => {
                element.classList.remove('small');
                element.classList.remove('medium');
                element.classList.remove('large');
                element.classList.add(font_size);
            });
        });
        localStorage.setItem("font_size_class", font_size);
    }

}


app.config.globalProperties.$relationships = function () {
    return [
        "Father",
        "Mother",
        "Son",
        "Daughter",
        "Brother",
        "Sister",
        "Niece",
        "Nephew",
        "Friend",
    ];
}



app.config.globalProperties.$formatDate = function (date) {
    return moment(date).format('YYYY-MM-DD');
}
app.config.globalProperties.$formatTime = function (date) {
    return moment(date, 'HH:mm:ss').format('HH:mm');
}


app.mount('#app');

