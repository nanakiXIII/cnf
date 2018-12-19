import Vue from 'vue'

const state = {
    status: '',
    Fichier: {},
    info:{},
    Formulaire: {},
    errors: {},
    image:false,
}

const getters = {
    getFichier: state => state.Fichier,
    getFichierInfo: state => state.info,
    isFichierLoaded: state => !!state.Fichier.name,
    getReponseFichier: state => state.Formulaire,
    getErrorFichier: state => state.errors,
}

const actions = {
    FichierRequest: ({commit, dispatch}, payload) => {
        commit('FichierRequest')
        if(payload.ftp == 'ftp'){
            var url = '/api/administration/Fichier/ftp'
        }
        if(payload.ftp == 'MiseAJour'){
            var url = '/api/administration/Fichier/ftp/update'
        }
        axios.get(url)
            .then((resp) => {
                commit('FichierSuccess', resp.data);
            })
            .catch((err) => {
                commit('FichierError');
                // if resp is unauthorized, logout, to
                //dispatch('authLogout')
            })

    },
    FormulaireFichierRequest: ({commit, dispatch}, payload) => {
        commit('FormulaireFichierRequest')
        var url = '/api/administration/Fichier'
        if(payload.action){
            let data = { 'data': payload}
            if(payload.actionEpisode=='nouveauEpisode'){
                data = {
                    'action':payload.actionEpisode,
                    'idSerie':payload.idSerie,
                    'idSaison':payload.idSaison,
                    'name':payload.name,
                    'numero':payload.numero,
                    'type':payload.type,
                    'dvd':payload.dvd,
                    'hd':payload.hd,
                    'fhd':payload.fhd,
                    'publication':payload.publication,
                    'streaming':payload.streaming,
                }
            }

            axios.post(url, data)
                .then((resp) => {
                    commit('FormulaireFichierSuccess', resp.data);
                })
                .catch((err) => {
                    commit('FormulaireFichierError', err.response.data);
                    reject(err);
                    // if resp is unauthorized, logout, to
                    //dispatch('authLogout')
                })



        }

    },

}

const mutations = {
    FichierRequest: (state) => {
        state.status = 'loading';
    },
    FichierSuccess: (state, resp) => {

        if(state.image){
            state.image = false
            state.status = 'success';
            Vue.set(state, 'info', resp);
        }else{
            state.status = 'success';
            Vue.set(state, 'Fichier', resp);
        }

    },
    FichierError: (state) => {
        state.status = 'error';
    },
    FormulaireFichierRequest: (state) => {
        state.status = 'loading';
    },
    FormulaireFichierSuccess: (state, resp) => {
        state.status = 'success';
        Vue.set(state, 'Formulaire', resp);
    },
    FormulaireFichierError: (state, err) => {

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