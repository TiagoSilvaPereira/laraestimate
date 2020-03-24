require('./bootstrap');
require('./helpers');

/**
 * Loading Adapters
 */
require('./adapters/ToastAdapter');

window.Vue = require('vue');

import Lang from 'lang.js';

window.translate = new Lang({
    messages: App.localizationData,
    locale: App.defaultLocale,
    fallback: App.fallbackLocale
});

Vue.prototype.trans = window.translate;

/**
 * Auto loading components
 */
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
});

window.onload = () => {
    if(App.messages.success) {
        toast.success(App.messages.success);
    }

    if(App.messages.errors && App.messages.errors.length) {
        console.log('here')
        App.messages.errors.forEach(error => toast.error(error));
    }

}