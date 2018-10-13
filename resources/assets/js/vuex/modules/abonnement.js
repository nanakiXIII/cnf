import Vue from 'vue'

const state = {
    status: '',
    suivis: {}
}

const getters = {
    getSuivis: state => state.suivis,
    isSuivisLoaded: state => !!state.suivis.name,
}

const actions = {
    SuivisRequest: ({commit, dispatch}) => {
        commit('abonnementRequest')
        axios.get('/api/compte')
            .then((resp) => {
                commit('abonnementSuccess', resp.data);
            })
            .catch((err) => {
                commit('abonnementError');
                // if resp is unauthorized, logout, to
                dispatch('authLogout')
            })
    },
}

const mutations = {
    abonnementRequest: (state) => {
        state.status = 'loading';
    },
    abonnementSuccess: (state, resp) => {
        state.status = 'success';
        Vue.set(state, 'suivis', resp);
    },
    abonnementError: (state) => {
        state.status = 'error';
    }
}

export default {
    state,
    getters,
    actions,
    mutations,
}