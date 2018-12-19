import Vue from 'vue'

const state = {
    status: '',
    Statistique: {},
    info:{},
    errors: {},
}

const getters = {
    getStatistique: state => state.Statistique,
    isStatistiqueLoaded: state => !!state.Statistique.name,
    getErrorStatistique: state => state.errors,
}

const actions = {
    StatistiqueRequest: ({commit, dispatch}, payload) => {
        commit('StatistiqueRequest')
        if(payload.data == 'serie'){
            var url = '/api/administration/Series/detail/'+payload.type+'/'+payload.slug+'/statistique'
        }

        axios.get(url)
            .then((resp) => {
                commit('StatistiqueSuccess', resp.data);
            })
            .catch((err) => {
                commit('StatistiqueError');
                // if resp is unauthorized, logout, to
                dispatch('authLogout')
            })

    },

}

const mutations = {
    StatistiqueRequest: (state) => {
        state.status = 'loading';
    },
    StatistiqueSuccess: (state, resp) => {
        state.status = 'success';
        Vue.set(state, 'Statistique', resp);

    },
    StatistiqueError: (state) => {
        state.status = 'error';
    },
}

export default {
    state,
    getters,
    actions,
    mutations,
}