import Vue from 'vue'

const state = {
    status: '',
    News: {}
}

const getters = {
    getNews: state => state.News,
    isNewsLoaded: state => !!state.News.name,
}

const actions = {
    NewsRequest: ({commit, dispatch}, payload) => {
        commit('newRequest')
        axios.get('/api/news?page='+payload.nextPage)
            .then((resp) => {
                commit('newSuccess', resp.data);
            })
            .catch((err) => {
                commit('newError');
                // if resp is unauthorized, logout, to
                dispatch('authLogout')
            })
    },

}

const mutations = {
    newRequest: (state) => {
        state.status = 'loading';
    },
    newSuccess: (state, resp) => {
        state.status = 'success';
        Vue.set(state, 'News', resp);
    },
    newError: (state) => {
        state.status = 'error';
    }
}

export default {
    state,
    getters,
    actions,
    mutations,
}