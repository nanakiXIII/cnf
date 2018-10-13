import Vue from 'vue';
import VueRouter from 'vue-router'
import {store} from '../vuex/store'

Vue.use(VueRouter);

const ifNotAuthenticated = (to, from, next) => {
    if (!store.getters.isAuthenticated) {
        next();
        return
    }
    next('/dashboard')
}

const ifAuthenticated = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        next();
        return
    }
    next('/login')
}

const ifAuthOrNot = (to, from, next) => {
    if (store.getters.isAuthenticated || !store.getters.isAuthenticated) {
        next();
        return
    }
}


import Login from './../components/auth/login'
import Register from './../components/auth/register'
import PasswordEmail from '../components/auth/password/email'
import PasswordReset from '../components/auth/password/reset'
import theme from './../components/layout/theme'
import AdminLayout from './../components/layout/layout'
import Dashboard from './../components/dashboard'
import ListeSerie from './../components/serie/listeComponent.vue'

const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        beforeEnter: ifNotAuthenticated,
    },
    {
        path: '/',
        name: 'register',
        component: Register,
        beforeEnter: ifNotAuthenticated,
    },
    {
        path: '/password/reset/',
        name: 'password-email',
        component: PasswordEmail,
        beforeEnter: ifNotAuthenticated,
    },
    {
        path: '/password/reset/:token',
        name: 'password-reset',
        component: PasswordReset,
        beforeEnter: ifNotAuthenticated,
    },
    {
        path: '/serie/:type',
        name: 'serie',
        component: ListeSerie,
        beforeEnter: ifAuthOrNot,
    },
    {
        path: '/',
        name: 'layout',
        component: theme,
        beforeEnter: ifAuthOrNot,
        children: [
            {
                path: '/dashboard',
                name: 'dashboard',
                component: Dashboard,
                beforeEnter: ifAuthenticated
            },

        ]
    },

];



export const router = new VueRouter({
    mode: 'history',
    routes
});

