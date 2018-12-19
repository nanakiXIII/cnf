import Vue from 'vue';
import Vuex from 'vuex';

import user from './modules/user'
import auth from './modules/auth'
import abonnement from './modules/abonnement'
import news from './modules/news'
import serie from './modules/serie'
import compte from './modules/compte'

import AdminMembre from './modules/administration/membres'
import AdminSeries from './modules/administration/serie'
import AdminStatistique from './modules/administration/statistique'
import AdminFichier from './modules/administration/fichier'

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        user,
        auth,
        abonnement,
        serie,
        compte,
        news,
        AdminMembre,
        AdminSeries,
        AdminStatistique,
        AdminFichier,
    }
});