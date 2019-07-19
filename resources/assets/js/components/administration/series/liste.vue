<template>
    <div class="container mt-5">
        <div class="btn-group btn-group-justified" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <router-link :to="{name:'AdminSerieADD'}"
                             type="button"
                        class="btn btn-default"
                >
                    <i class="fas fa-plus text-success"></i> Nouveau Projet
                </router-link>
                <button type="button"
                        class="btn btn-default"
                        v-bind:class="{'colorise':(type === 'all')}"
                        @click="type = 'all'"
                >
                    <i class="fas fa-globe"></i> All
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button"
                        class="btn btn-default"
                        v-bind:class="{'colorise':(type === 'Animes')}"
                        @click="type = 'Animes'"
                >
                    <i class="fas fa-video"></i> Animés
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button"
                        class="btn btn-default"
                        v-bind:class="{'colorise':(type === 'Scantrad')}"
                        @click="type = 'Scantrad'"
                >
                    <i class="fas fa-paint-brush"></i> Scantrad</button>
            </div>
            <div class="btn-group" role="group">
                <button type="button"
                        class="btn btn-default"
                        v-bind:class="{'colorise':(type === 'Light-Novel')}"
                        @click="type = 'Light-Novel'"
                >
                    <i class="fas fa-book-open"></i> Light Novel
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button"
                        class="btn btn-default"
                        v-bind:class="{'colorise':(type === 'Visual-Novel')}"
                        @click="type = 'Visual-Novel'"
                >
                    <i class="fas fa-gamepad"></i> Visual Novel
                </button>
            </div>
        </div> <!-- Choix du type -->
        <div class="row">
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col text-center">Visible</th>
                    <th scope="col text-center">Titre</th>
                    <th scope="col text-center">Etat</th>
                    <th scope="col text-center">Type</th>
                    <th scope="col text-center">Fichiers</th>
                    <th scope="col text-center">Options</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="serie in informations.data" v-if="type == 'all' || type == serie.type">
                    <td v-bind:class="{ 'table-success': serie.publication == '1', 'table-warning ': serie.publication == '0' }">
                        <span class="text-primary" v-if="serie.publication == 0">Non</span>
                        <span class="text-primary" v-if="serie.publication == 1">Oui</span>
                    </td>
                    <td>
                        {{serie.titre}}
                    </td>
                    <td>
                        <span class="text-primary" v-if="serie.etat == 0">En Cours</span>
                        <span class="text-success" v-if="serie.etat == 1">Terminé</span>
                        <span class="text-warning" v-if="serie.etat == 2">Abandonné</span>
                        <span class="text-danger" v-if="serie.etat == 3">Licencié</span><br>
                    </td>
                    <td class="text-center">
                        {{serie.type}}
                    </td>
                    <td>
                        <b>{{serie.episodes}}</b>
                        /
                        <b v-if="serie.episode != 0">{{ serie.episode }}</b>
                        <b v-else="">???</b>
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <router-link :to="{ name: 'AdminSerieFiche', params: { slug:serie.slug, id:serie.id } }" class="no-decoration">
                                <button type="button" class="btn btn-secondary">Modifier</button>
                            </router-link>
                            <router-link :to="{ name: 'AdminSerieFichier', params: { slug:serie.slug, id:serie.id } }" class="no-decoration">
                                <button type="button" class="btn btn-secondary">Fichiers</button>
                            </router-link>
                            <button type="button" class="btn btn-secondary" @click="getDestroy(serie)">Supprimer</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>
<script>
    export default {
        data(){
            return {
                type:'all',
                informations:'',
            }


        },
        computed: {

        },
        watch:{
            informations(){
                this.$parent.titre = "Gestion des projets"
            }
        },
        methods: {
            getInfo(){
                axios.get('/api/administration/series')
                    .then(response => {
                        this.informations = response.data
                    })

            },
            getDestroy(info){
                let options = {
                    okText: 'Confirmer',
                    cancelText: 'Fermer',
                    type:'hard',
                    verification: 'Supprimer',
                    verificationHelp: 'Tapez [+:verification] avant de confirmer',
                    message: 'Etes-vous sur de vouloir supprimer '+info.titre,
                    clicksCount: 1,
                    backdropClose: true,
                };
                this.$dialog.confirm(options.message,options)
                    .then(() => {
                        axios.delete('/api/administration/series/'+info.id)
                            .then(response =>{
                                if(response.data.success == true){
                                    this.getInfo()
                                }else{

                                }
                            })
                    })
            }
        },
        mounted(){
            this.$parent.titre = "Chargement ..."
            this.getInfo()
        }
    }
</script>