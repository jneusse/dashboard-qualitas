/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
require('materialize-css');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('dashboard-component', require('./components/DashboardComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

M.AutoInit();
//mostrar alerta de cierre de sessión a los 14min y 15 seg de inactividad
// y a los 14min y 30 seg se cierra sessión

$(document).ready(function() {
    setTimeout(function(){
        alert('Su sesión esta apunto de caducar debido a inactividad');
        setTimeout(logout(), 855000);
    }, 870000);
});

function logout(){
    $('#logout-form').submit();
}

function reload(segs) {
    setTimeout(function() {
        location.reload();
    }, parseInt(segs) * 1000);
}

// 14min 15seg = 855000
// 14min 30seg = 870000
