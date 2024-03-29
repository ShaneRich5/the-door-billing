
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('category-form', require('./components/CategoryForm'));
Vue.component('quick-delete-form', require('./components/QuickDeleteForm'));

Vue.component('tag-container', require('./components/tags/TagContainer'));
Vue.component('tag-list', require('./components/tags/TagList'));
Vue.component('tag-form', require('./components/tags/TagForm'));
Vue.component('printer-settings', require('./components/settings/PrinterSettings'));
Vue.component('delivery-cost-settings', require('./components/settings/DeliveryCostSettings'));
Vue.component('zip-code-cost-form', require('./components/ZipCodeCostForm'));
Vue.component('zip-code-cost-list', require('./components/ZipCodeCostList'));

const app = new Vue({
    el: '#app'
});
