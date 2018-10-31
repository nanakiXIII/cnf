import Vue from 'vue'

const state = {
    status: '',
    compte: {}
}

const getters = {
    getcompte: state => state.compte,
    iscompteLoaded: state => !!state.compte,
}

const actions = {

    compteRequest: ({commit, dispatch}, payload) => {
        let actionUrl= '/api/compte';

        if (!payload.log && payload.action == "streaming"){
            actionUrl= '/api/streaming';
        }

        let data = {
            'action':payload.action,
            'abo':payload.abo,
            'serie_id':payload.serie_id
        }
        if(payload.action=='streaming'){
            data = {
                'action':payload.action,
                'serie_id':payload.serie_id,
                'episode_id':payload.episode_id,
                'time': payload.time,
            }
        }
        if(payload.action=='theme'){
            data = {
                'action':payload.action,
                'theme':payload.theme,
            }
        }

        commit('compteRequest')
        axios.post(actionUrl, data)
            .then((resp) => {
                commit('compteSuccess', resp);
            })
            .catch((err) => {
                commit('compteError');
                // if resp is unauthorized, logout, to
                //dispatch('authLogout')
            })


    },

}

const mutations = {
    compteRequest: (state) => {
        state.status = 'loading';
    },
    compteSuccess: (state, resp) => {
        state.status = 'success';
        Vue.set(state, 'compte', resp);
    },
    compteError: (state) => {
        state.status = 'error';
    }
}

export default {
    state,
    getters,
    actions,
    mutations,
}