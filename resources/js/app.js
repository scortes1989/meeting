require('./bootstrap');

window.Vue = require('vue').default;

Vue.component('meetings', require('./meetings').default);

const app = new Vue({
    el: '#app',
});