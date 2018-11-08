import Vue from 'vue';
import VueRouter from 'vue-router'
import {store} from '../vuex/store'

Vue.use(VueRouter);

const ifNotAuthenticated = (to, from, next) => {
    if (!store.getters.isAuthenticated) {
        next();
        return
    }
    next('/user')
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

import Accueil from './../components/accueil/accueil'

import ListeSerie from './../components/serie/listeComponent'
import detailComponent from './../components/serie/detailComponent'
import Streaming from './../components/serie/streamingComponent'

import user from './../components/compte/layout'
import userSuivis from './../components/compte/component/suivis'
import userHistorique from './../components/compte/component/historique'
import userParametre from './../components/compte/component/parametre'

const routes = [
    {
        path: '/',
        name: 'accueil',
        component: Accueil,
        beforeEnter: ifAuthOrNot,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        beforeEnter: ifNotAuthenticated,
    },
    {
        path: '/register',
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
        component: theme,
        beforeEnter: ifAuthOrNot,
        children:[
            {
                path:'',
                name:'serie',
                component: ListeSerie,
                beforeEnter: ifAuthOrNot
            },
            {
                path:':slug',
                name:'',
                component: theme,
                beforeEnter: ifAuthOrNot,
                children:[
                    {
                        path:'',
                        name:'serieDetail',
                        component: detailComponent,
                        beforeEnter: ifAuthOrNot
                    },
                    {
                        path:'saison-:saison/episode-:episode',
                        name:'streaming',
                        component: Streaming,
                        beforeEnter: ifAuthOrNot
                    },
                ]
            },


        ]
    },
    {
        path: '/user',
        component: user,
        beforeEnter: ifAuthenticated,
        children: [
            {
                path: '',
                name: 'dashboard',
                component: userSuivis,
                beforeEnter: ifAuthenticated
            },
            {
                path: 'historique',
                name: 'historique',
                component: userHistorique,
                beforeEnter: ifAuthenticated
            },
            {
                path: 'parametre',
                name: 'parametre',
                component: userParametre,
                beforeEnter: ifAuthenticated
            },

        ]
    },

];



export const router = new VueRouter({
    mode: 'history',
    routes
});

