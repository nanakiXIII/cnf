import Vue from 'vue'

const state = {
    status: '',
    Series: {},
    info:{},
    Formulaire: {},
    errors: {},
    image:false,
}

const getters = {
    getSeries: state => state.Series,
    getSeriesInfo: state => state.info,
    isSeriesLoaded: state => !!state.Series.name,
    getReponseSerie: state => state.Formulaire,
    getErrorSerie: state => state.errors,
}

const actions = {
    SeriesRequest: ({commit, dispatch}, payload) => {
        commit('seriesRequest')
        if(payload.data == 'Ajouter'){
            var url = '/api/administration/Series'
        }
        else if(payload.data == 'image'){
            var url = '/api/administration/Series/image/'+encodeURI(payload.option)
            state.image = true
        }
        else if(payload.data == "detail"){
            var url = '/api/administration/Series/detail/'+payload.type+'/'+payload.slug
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
                }
            }
            if(payload.action=='nouvelleSaison'){
                url = '/api/administration/saison'
                data = {
                    'action':payload.action,
                    'name':payload.name,
                    'numero':payload.numero,
                    'idSerie':payload.idSerie,
                    'type':payload.type,
                    'nosaison':payload.nosaison,
                    'publication':payload.publication,
                }
            }
            if(payload.action=='modifierSaison'){
                url = '/api/administration/saison'
                data = {
                    'action':payload.action,
                    'name':payload.name,
                    'numero':payload.numero,
                    'idSerie':payload.idSerie,
                    'type':payload.type,
                    'nosaison':payload.nosaison,
                    'publication':payload.publication,
                    'id':payload.id,
                    '_method': 'PUT'

                }
            }
            if(payload.action=='deleteSaison'){
                console.log(payload.idSerie)
                url = '/api/administration/saison'
                data = {
                    'action':payload.action,
                    'idSerie':payload.idSerie,
                    'id':payload.id,
                    '_method': 'DELETE'

                }
            }
            if(payload.action == 'newSerie' || payload.action == 'modifierSerie'){
                data = new FormData();
                data.append('action', payload.action)
                data.append('titre', payload.titre)
                data.append('titre_alternatif', payload.titre_alternatif)
                data.append('titre_original', payload.titre_original)
                data.append('studio', payload.studio)
                data.append('auteur', payload.auteur)
                data.append('annee', payload.annee)
                data.append('synopsis', payload.synopsis)
                data.append('staff', payload.staff)
                data.append('etat', payload.etat)
                data.append('type', payload.type)
                data.append('episode', payload.episode)
                data.append('oav', payload.oav)
                data.append('film', payload.films)
                data.append('bonus', payload.bonus)
                data.append('ln', payload.ln)
                data.append('scan', payload.scan)
                data.append('vn', payload.vn)
                data.append('genre', payload.genre)
                data.append('imageChoix', payload.imageChoix)
                data.append('imagecheck', payload.imagecheck)
                data.append('file', payload.file)
                data.append('publication', payload.publication)
                if(payload.action == 'modifierSerie'){
                    data.append('_method', 'PUT');
                    data.append('id', payload.id);
                    data.append('genreID', payload.genreID);
                }


            }
            if(payload.action == "delete"){
                console.log(payload.idDelete)
                data = {
                    'action':payload.action,
                    'id':payload.idDelete,
                    '_method': 'DELETE'
                }
            }

            var config =  { headers: {'Content-Type': 'application/json;charset=utf-8;multipart/form-data; boundary=' + Math.random().toString().substr(2)}}
                axios.post(url, data,config)
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

        if(state.image){
            state.image = false
            state.status = 'success';
            Vue.set(state, 'info', resp);
        }else{
            state.status = 'success';
            Vue.set(state, 'Series', resp);
        }

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