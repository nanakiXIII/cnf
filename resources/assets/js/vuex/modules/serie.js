import Vue from 'vue'

const state = {
    status: '',
    serie: {}
}

const getters = {
    getSerie: state => state.serie,
    isSerieLoaded: state => !!state.serie.name,
}

const actions = {
    SerieRequest: ({commit, dispatch}, payload) => {
        commit('serieRequest')
        axios.get('/api/serie/'+payload.type)
            .then((resp) => {
                commit('serieSuccess', resp.data);
            })
            .catch((err) => {
                commit('serieError');
                // if resp is unauthorized, logout, to
                //dispatch('authLogout')
            })
    },
    SerieInfoRequest: ({commit, dispatch}, payload) => {
        commit('serieRequest')
        axios.get('/api/serie/'+payload.type+'/'+payload.slug)
            .then((resp) => {
                commit('serieSuccess', resp.data);
            })
            .catch((err) => {
                commit('serieError');
                // if resp is unauthorized, logout, to
                //dispatch('authLogout')
            })
    },
    SerieRequestLog: ({commit, dispatch}, payload) => {
        commit('serieRequestLog')
        axios.get('/api/compte/serie/'+payload.type)
            .then((resp) => {
                commit('serieSuccessLog', resp.data);
            })
            .catch((err) => {
                commit('serieErrorLog');
                // if resp is unauthorized, logout, to
                //dispatch('authLogout')
            })
    },
    SerieInfoRequestLog: ({commit, dispatch}, payload) => {
        commit('serieRequestLog')
        axios.get('/api/compte/serie/'+payload.type+'/'+payload.slug)
            .then((resp) => {
                commit('serieSuccessLog', resp.data);
            })
            .catch((err) => {
                commit('serieErrorLog');

                // if resp is unauthorized, logout, to
                //dispatch('authLogout')
            })
    },
}

const mutations = {
    serieRequest: (state) => {
        state.status = 'loading';
    },
    serieSuccess: (state, resp) => {
        state.status = 'success';
        Vue.set(state, 'serie', resp);
    },
    serieError: (state) => {
        state.status = 'error';
    },
    serieRequestLog: (state) => {
        state.status = 'loading';
    },
    serieSuccessLog: (state, resp) => {
        state.status = 'success';
        Vue.set(state, 'serie', resp);
    },
    serieErrorLog: (state) => {
        state.status = 'error';
    }
}

export default {
    state,
    getters,
    actions,
    mutations,
}