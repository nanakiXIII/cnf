import Vue from 'vue'

const state = {
    status: '',
    Membres: {},
    Formulaire: {},
    errors: {}
}

const getters = {
    getMembres: state => state.Membres,
    isMembresLoaded: state => !!state.Membres.name,
    getReponse: state => state.Formulaire,
    getErrorM: state => state.errors,
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
    FormulaireRequest: ({commit, dispatch}, payload) => {
        commit('FormulaireRequest')
        var url = '/api/administration/membres'
        if(payload.action){
            let data = {}
            if(payload.action=='roles'){
                data = {
                    'action':payload.action,
                    'checkbox':payload.roleID,
                    'user_id':payload.id,
                    'equipe': payload.equipe,
                    'postes': payload.posteID
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
                    commit('Formulairesuccess', resp.data);
                })
                .catch((err) => {
                    commit('FormulaireError', err.response.data);
                    reject(err);
                    // if resp is unauthorized, logout, to
                    //dispatch('authLogout')
                })
            }

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
    },
    FormulaireRequest: (state) => {
        state.status = 'loading';
    },
    Formulairesuccess: (state, resp) => {
        state.status = 'success';
        Vue.set(state, 'Formulaire', resp);
    },
    FormulaireError: (state, err) => {

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