import Vue from 'vue';
import VueRouter from 'vue-router'
import {store} from '../vuex/store'

Vue.use(VueRouter);
function found(tab, element) {
    let response = tab.indexOf(element)
    if (response >= 0){
        return true
    }
    else{
        return false
    }
}
const ifNotAuthenticated = (to, from, next) => {
    if (!store.getters.isAuthenticated) {
        next();
        return
    }
    next('/user')
}
const admin = async (to, from, next) => {
    if (store.getters.isAuthenticated) {
        if(store.getters.getProfile.data == undefined){
            setTimeout(function() {
                admin(to,from,next);
            }, 1000);
            return
        }
        if (found(store.getters.getProfile.data.permission, 'Administration')) {
            next();
            return
        }
        next('/user')
        return
    }
    next('/login')
}
const serie = (to, from, next) => {

    if (store.getters.isAuthenticated) {
        if(store.getters.getProfile.data == undefined){
            setTimeout(function() {
                admin(to,from,next);
            }, 1000);
            return
        }
        if (found(store.getters.getProfile.data.permission, 'GestionDesSeries')) {
            next();
            return
        }
        next('/administration')
        return
    }
    next('/login')
}
const fichier = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        if(store.getters.getProfile.data == undefined){
            setTimeout(function() {
                admin(to,from,next);
            }, 1000);
            return
        }
        if (found(store.getters.getProfile.data.permission, 'GestionDesFichiers')) {
            next();
            return
        }
        next('/administration')
        return
    }
    next('/login')
}
const users = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        if(store.getters.getProfile.data == undefined){
            setTimeout(function() {
                admin(to,from,next);
            }, 1000);
            return
        }
        if (found(store.getters.getProfile.data.permission, 'GestionDesUsers')) {
            next();
            return
        }
        next('/administration')
        return
    }
    next('/login')
}
const news = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        if(store.getters.getProfile.data == undefined){
            setTimeout(function() {
                admin(to,from,next);
            }, 1000);
            return
        }
        if (found(store.getters.getProfile.data.permission, 'GestionDesNews')) {
            next();
            return
        }
        next('/administration')
        return
    }
    next('/login')
}
const genres = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        if(store.getters.getProfile.data == undefined){
            setTimeout(function() {
                admin(to,from,next);
            }, 1000);
            return
        }
        if (found(store.getters.getProfile.data.permission, 'GestionDesGenres')) {
            next();
            return
        }
        next('/administration')
        return
    }
    next('/login')
}
const commentaires = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        if(store.getters.getProfile.data == undefined){
            setTimeout(function() {
                admin(to,from,next);
            }, 1000);
            return
        }
        if (found(store.getters.getProfile.data.permission, 'GestionDesCommentaires')) {
            next();
            return
        }
        next('/administration')
        return
    }
    next('/login')
}
const avancements = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        if(store.getters.getProfile.data == undefined){
            setTimeout(function() {
                admin(to,from,next);
            }, 1000);
            return
        }
        if (found(store.getters.getProfile.data.permission, 'GestionDesAvancements')) {
            next();
            return
        }
        next('/administration')
        return
    }
    next('/login')
}
const gestion = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        if(store.getters.getProfile.data == undefined){
            setTimeout(function() {
                admin(to,from,next);
            }, 1000);
            return
        }
        if (found(store.getters.getProfile.data.permission, 'Gestion')) {
            next();
            return
        }
        next('/administration')
        return
    }
    next('/login')
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
import News from './../components/accueil/news'
import equipe from './../components/equipe/equipes'
import contact from './../components/contact/contact'

import ListeSerie from './../components/serie/listeComponent'
import detailComponent from './../components/serie/detailComponent'
import Streaming from './../components/serie/streamingComponent'
import Lecture from './../components/serie/lecture'
import lightNovel from './../components/serie/light-novel'

import user from './../components/compte/layout'
import userInfo from './../components/compte/component/information'
import userSuivis from './../components/compte/component/suivis'
import userHistorique from './../components/compte/component/historique'
import userLecture from './../components/compte/component/lecture'
import userTropher from './../components/compte/component/tropher'
import userParametre from './../components/compte/component/parametre'

import Administration from './../components/administration/dashboard'
import Utilisateurs from './../components/administration/membres/utilisateurs'
import Postes from './../components/administration/membres/poste'
import Permission from './../components/administration/membres/permission'
import Role from './../components/administration/membres/roles'
import AdminGenres from './../components/administration/genres/genres'

import SerieAjouter from './../components/administration/series/newSerie'
import FicheSerieAdmin from './../components/administration/series/fiche'
import ListeSerieAdmin from './../components/administration/series/liste'
import FichierAdmin from './../components/administration/series/fichier'

import AdminNews from './../components/administration/news/news'
import AdminNewNews from './../components/administration/news/newNews'
import AdminModNews from './../components/administration/news/modnews'

import Avancements from './../components/administration/avancements/avancements'
import gestionVideo from './../components/administration/gestion/streaming'

import Commentaire from './../components/administration/comments/comments'


const routes = [
    {
        path: '/',
        name: 'accueil',
        component: Accueil,
    },
    {
        path: '/news/:slug',
        name: 'news',
        component: News,
    },
    {
        path: '/equipes',
        name: 'equipes',
        component: equipe,
    },
    {
        path: '/contact',
        name: 'contact',
        component: contact,
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
        path: '/projets/',
        component: theme,
        beforeEnter: ifAuthOrNot,
        children:[
            {
                path:'',
                name:'projets',
                component: ListeSerie,
                beforeEnter: ifAuthOrNot
            },
            {
                path:':type/:slug',
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
                    {
                        path:'tome-:saison/chapitre-:episode',
                        name:'lecture',
                        component: Lecture,
                        beforeEnter: ifAuthOrNot
                    },
                    {
                        path:'tomes-:saison/chapitres-:episode',
                        name:'lightNovel',
                        component: lightNovel,
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
                path: 'telechargements',
                name: 'userInformation',
                component: userInfo,
                beforeEnter: ifAuthenticated
            },
            {
                path: 'suivis',
                name: 'dashboard',
                component: userSuivis,
                beforeEnter: ifAuthenticated
            },
            {
                path: 'streaming',
                name: 'historique',
                component: userHistorique,
                beforeEnter: ifAuthenticated
            },
            {
                path: 'lecture',
                name: 'userLecture',
                component: userLecture,
                beforeEnter: ifAuthenticated
            },
            {
                path: 'success',
                name: 'userSuccess',
                component: userTropher,
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
    {
        path: '/administration',
        name: 'administration',
        component: Administration,
        beforeEnter: admin,
    },
    {
        path: '/administration/utilisateurs',
        name: 'utilisateurs',
        component: Utilisateurs,
        beforeEnter: users,
    },
    {
        path: '/administration/postes',
        name: 'AdminPostes',
        component: Postes,
        beforeEnter: users,
    },
    {
        path: '/administration/permissions',
        name: 'AdminPermissions',
        component: Permission,
        beforeEnter: gestion,
    },
    {
        path: '/administration/roles',
        name: 'AdminRoles',
        component: Role,
        beforeEnter: users,
    },
    {
        path: '/administration/news',
        name: 'AdminNews',
        component: AdminNews,
        beforeEnter: news,
    },
    {
        path: '/administration/news/add',
        name: 'AdminNewNews',
        component: AdminNewNews,
        beforeEnter: news,
    },
    {
        path: '/administration/news/:id/:slug',
        name: 'AdminModNews',
        component: AdminModNews,
        beforeEnter: news,
    },
    {
        path: '/administration/genres',
        name: 'AdminGenres',
        component: AdminGenres,
        beforeEnter: genres,
    },
    {
        path: '/administration/series/add',
        name: 'AdminSerieADD',
        component: SerieAjouter,
        beforeEnter: serie,
    },
    {
        path: '/administration/series/',
        name: 'AdminSerie',
        component: ListeSerieAdmin,
        beforeEnter: serie,
    },
    {
        path: '/administration/series/:id/:slug/update',
        name: 'AdminSerieFiche',
        component: FicheSerieAdmin,
        beforeEnter: serie,
    },
    {
        path: '/administration/series/:id/:slug/fichiers',
        name: 'AdminSerieFichier',
        component: FichierAdmin,
        beforeEnter: fichier,
    },
    {
        path: '/administration/commentaires',
        name: 'AdminCommentaires',
        component: Commentaire,
        beforeEnter: commentaires,
    },
    {
        path: '/administration/avancements',
        name: 'AdminAvancements',
        component: Avancements,
        beforeEnter: avancements,
    },
    {
        path: '/administration/gestion/streaming',
        name: 'gestionVideo',
        component: gestionVideo,
        beforeEnter: gestion,
    },
];



export const router = new VueRouter({
    mode: 'history',
    routes
});

