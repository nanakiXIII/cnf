import Vue from 'vue';
import Vuex from 'vuex';

import user from './modules/user'
import auth from './modules/auth'
import abonnement from './modules/abonnement'
import serie from './modules/serie'
import compte from './modules/compte'

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        user,
        auth,
        abonnement,
        serie,
        compte,
    }
});