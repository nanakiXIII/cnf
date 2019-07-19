/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


import { router } from './router/routes';
import { store } from './vuex/store';
import vueMoment from 'vue-moment'
import Notifications from 'vue-notification'
import moment from 'moment'
import Meta from 'vue-meta'
import NProgress from 'nprogress'
import '../../../node_modules/nprogress/nprogress.css'
import VuejsDialog from 'vuejs-dialog';
import 'vuejs-dialog/dist/vuejs-dialog.min.css';

Vue.use(vueMoment,{moment});
Vue.use(Meta);
Vue.use(Notifications);
Vue.use(VuejsDialog);
Vue.moment.locale('fr');
Vue.use(Meta, {
    keyName: 'metaInfo', // the component option name that vue-meta looks for meta info on.
    attribute: 'data-vue-meta', // the attribute name vue-meta adds to the tags it observes
    ssrAttribute: 'data-vue-meta-server-rendered', // the attribute name that lets vue-meta know that meta info has already been server-rendered
    tagIDKeyName: 'vmid' // the property name that vue-meta uses to determine whether to overwrite or append a tag
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('navbar-component', require('./components/layout/navbar.vue'));
Vue.component('layout-component', require('./components/layout/layout.vue'));
Vue.component('passport-clients', require('./components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

router.beforeResolve((to, from, next) => {
    if (to.name) {
        NProgress.start()
    }
    next()
})

router.afterEach((to, from) => {
    NProgress.done()
})
const app = new Vue({
    el: '#app',
    // voir pour chargement ...

    created(){
        if (this.$store.getters.isAuthenticated) {
            this.$store.dispatch('userRequest');
        }
    },
    store,
    router
});
