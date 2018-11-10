import Vue from 'vue'

const state = {
    status: '',
    Membres: {}
}

const getters = {
    getMembres: state => state.Membres,
    isMembresLoaded: state => !!state.Membres.name,
}

const actions = {
    MembresRequest: ({commit, dispatch}, payload) => {
        commit('membreRequest')
        if(payload.data == 'liste'){
            var url = '/api/administration/membres'
        }
        else{
            var url = '/api/administration/membres'
        }
        axios.get(url)
            .then((resp) => {
                commit('Membresuccess', resp.data);
            })
            .catch((err) => {
                commit('membreError');
                // if resp is unauthorized, logout, to
                dispatch('authLogout')
            })
    },

}

const mutations = {
    membreRequest: (state) => {
        state.status = 'loading';
    },
    Membresuccess: (state, resp) => {
        state.status = 'success';
        Vue.set(state, 'Membres', resp);
    },
    membreError: (state) => {
        state.status = 'error';
    }
}

export default {
    state,
    getters,
    actions,
    mutations,
}