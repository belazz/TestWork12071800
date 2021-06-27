import Vue from 'vue'
import App from './App.vue'
import router from 'vue-router';
import BootstrapVue from "bootstrap-vue";
import './css/project.scss';
import {ValidationProvider, ValidationObserver, extend} from 'vee-validate';
import {required, numeric} from 'vee-validate/dist/rules';
import VueConfirmDialog from 'vue-confirm-dialog';

extend('required', required);
extend('numeric', numeric);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
Vue.use(BootstrapVue);
Vue.use(VueConfirmDialog)
Vue.component('vue-confirm-dialog', VueConfirmDialog.default)

Vue.config.productionTip = false

new Vue({
  render: h => h(App),
  router
}).$mount('#app')
