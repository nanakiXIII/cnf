import Vue from 'vue'

const state = {
    status: '',
    Series: {},
    Formulaire: {},
    errors: {}
}

const getters = {
    getSeries: state => state.Series,
    isSeriesLoaded: state => !!state.Series.name,
    getReponseSerie: state => state.Formulaire,
    getErrorSerie: state => state.errors,
}

const actions = {
    SeriesRequest: ({commit, dispatch}, payload) => {
        commit('seriesRequest')
        if(payload.data == 'liste'){
            var url = '/api/administration/Series'
        }
        else{
            var url = '/api/administration/Series?data='+payload.data
        }
        axios.get(url)
            .then((resp) => {
                commit('Seriesuccess', resp.data);
            })
            .catch((err) => {
                commit('seriesError');
                // if resp is unauthorized, logout, to
                dispatch('authLogout')
            })

    },
    FormulaireSerieRequest: ({commit, dispatch}, payload) => {
        commit('FormulaireSerieRequest')
        var url = '/api/administration/Series'
        if(payload.action){
            let data = { 'data': payload}
            if(payload.action=='Information'){
                data = {
                    'action':payload.action,
                    'url':payload.url,
                    'choix':payload.choix,
                    'para': payload.info
                }
            }
            if(payload.action=='Aroles'){
                data = {
                    'action':payload.action,
                    'name':payload.name,
                }
            }
            if(payload.action=='rolesMod'){
                data = {
                    'id': payload.id,
                    'action':payload.action,
                    'permissions':payload.permissionsID,
                    'name':payload.name,
                }
            }
            if(payload.action=='postesMod'){
                data = {
                    'action':payload.action,
                    'site':payload.site,
                    'name':payload.name,
                    'id': payload.id,
                }
            }
            if(payload.action=='permissionMod'){
                data = {
                    'action':payload.action,
                    'name':payload.name,
                    'id': payload.id,
                }
            }
            if(payload.action=='postes'){
                data = {
                    'action':payload.action,
                    'site':payload.site,
                    'name':payload.name,
                }
            }
            if(payload.action=='permission'){
                data = {
                    'action':payload.action,
                    'name':payload.name,
                }
            }
            if(payload.action=='permissionDelete' || payload.action=='rolesDelete' || payload.action=='delete'){
                data = {
                    'action':payload.action,
                    'id':payload.id,
                }
            }
            axios.post(url, data)
                .then((resp) => {
                    commit('FormulaireSeriesuccess', resp.data);
                })
                .catch((err) => {
                    commit('FormulaireSerieError', err.response.data);
                    reject(err);
                    // if resp is unauthorized, logout, to
                    //dispatch('authLogout')
                })
        }

    },

}

const mutations = {
    seriesRequest: (state) => {
        state.status = 'loading';
    },
    Seriesuccess: (state, resp) => {
        state.status = 'success';
        Vue.set(state, 'Series', resp);
    },
    seriesError: (state) => {
        state.status = 'error';
    },
    FormulaireSerieRequest: (state) => {
        state.status = 'loading';
    },
    FormulaireSeriesuccess: (state, resp) => {
        state.status = 'success';
        Vue.set(state, 'Formulaire', resp);
    },
    FormulaireSerieError: (state, err) => {

        let errors={};

        state.errors=err.message

        state.status = 'error';
        state.hasLoadedOnce = true;
    }
}

export default {
    state,
    getters,
    actions,
    mutations,
}