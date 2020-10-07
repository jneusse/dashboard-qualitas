/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');
window.$ = require('jquery')
window.Vue = require('vue');
window.axios = require('axios');
require('materialize-css');
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

import locale from 'element-ui/lib/locale/lang/es'

Vue.use(ElementUI, { locale })

/** If Vuesax */
// import Vuesax from 'vuesax'

// import 'vuesax/dist/vuesax.css' //Vuesax styles
// Vue.use(Vuesax, {
//   // options here
// })

import Swal from 'sweetalert2/src/sweetalert2.js'
window.Swal = Swal
/** If vue-sweetalert2 */
// import VueSweetalert2 from 'vue-sweetalert2'
// Vue.use(VueSweetalert2);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('App', require('./components/App.vue').default);
Vue.component('Auth', require('./components/Auth.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import router from './routes'
const app = new Vue({
    el: '#app',
    router
});

export const EventBus = new Vue()
window.EventBus = EventBus

M.AutoInit();


document.addEventListener('DOMContentLoaded', function() {
    // window.addEventListener('click', function(e){
    //     if(e.target == document.querySelector('#modalCreate'))
    //     document.querySelector('#modalCreate').style.display = 'none'
    //     console.log("click");
    // })
    // console.log(document.querySelector('#modalCreate'));
});
