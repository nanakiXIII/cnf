<template>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-2 offset-sm-1">
                <router-link :to="{name:'AdminSerieADD'}">
                    <button type="button" class="btn btn-success btn-circle btn-xl mt-4"><i class="fas fa-plus"></i></button>
                </router-link>
            </div>
            <div class="col-md-9">
                <div class="bg-white p-3">
                    <nav class="nav nav-pills nav-justified border">
                        <a @click="type = 'all'" v-bind:class="{'router-link-exact-active router-link-active':(type === 'all')}" class="nav-item nav-link text-truncate cursor">
                            <b>All</b>
                        </a>
                        <a @click="type = 'Animes'" v-bind:class="{'router-link-exact-active router-link-active':(type === 'Animes')}" class="nav-item nav-link text-truncate cursor">
                            <b> Animés</b>
                        </a>
                        <a @click="type = 'Scantrad'" v-bind:class="{'router-link-exact-active router-link-active':(type === 'Scantrad')}" class="nav-item nav-link text-truncate cursor">
                            <b> Scantrad</b>
                        </a>
                        <a @click="type = 'Light-Novel'" v-bind:class="{'router-link-exact-active router-link-active':(type === 'Light-Novel')}" class="nav-item nav-link text-truncate cursor">
                            <b> Light Novel</b>
                        </a>
                        <a @click="type = 'Visual-Novel'" v-bind:class="{'router-link-exact-active router-link-active':(type === 'Visual-Novel')}" class="nav-item nav-link text-truncate cursor">
                            <b> Visual Novel</b>
                        </a>
                    </nav>
                    <nav class="nav nav-pills nav-justified border">
                        <a @click="team = 'cnf'" v-bind:class="{'router-link-exact-active router-link-active':(team === 'cnf')}" class="nav-item nav-link text-truncate cursor">
                            <b>Chuushin</b>
                        </a>
                        <a @click="team = 'set'" v-bind:class="{'router-link-exact-active router-link-active':(team === 'set')}" class="nav-item nav-link text-truncate cursor">
                            <b>SeedTeam</b>
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                    <tr class="text-center">
                        <th scope="col">Série</th>
                        <th scope="col">Visible</th>
                        <th scope="col">Etat</th>
                        <th scope="col">Type</th>
                        <th scope="col">Fichiers</th>
                        <th scope="col">Team</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="serie in informations.data" v-if="(type == 'all' || type == serie.type) && (team == serie.team)" >
                        <th scope="row" style="max-width: 25%">
                            <div class="media align-items-center">
                                <img alt="Image placeholder" height="50px" width="50px" class="rounded-circle mr-4 ml-3 shadow-sm" :src="'https://www.chuushin-no-fansub.fr/storage/images/images/'+serie.image">
                                <div class="media-body">
                                    <span class="mb-0 text-sm text-break">{{serie.titre}}</span>
                                </div>
                            </div>
                        </th>
                        <td class="text-center">
                           <span class="badge badge-dot mr-4">
                               <template v-if="serie.publication == 1">
                                   <i class="text-success fas fa-circle"></i>
                               </template>
                               <template v-else>
                                    <i class="text-danger fas fa-circle"></i>
                               </template>

                          </span>
                        </td>
                        <td class="text-center">
                            <span class="text-primary" v-if="serie.etat == 0">En Cours</span>
                            <span class="text-success" v-if="serie.etat == 1">Terminé</span>
                            <span class="text-warning" v-if="serie.etat == 2">Abandonné</span>
                            <span class="text-danger" v-if="serie.etat == 3">Licencié</span>
                        </td>
                        <td class="text-center">
                            {{serie.type}}
                        </td>
                        <td class="text-center">
                            <b>{{serie.episodes}}</b>
                            /
                            <b v-if="serie.episode != 0">{{ serie.episode }}</b>
                            <b v-else="">???</b>
                        </td>
                        <td class="text-center">
                           {{serie.team}}
                        </td>
                        <td class="text-right">
                            <div class="dropdown">
                                <a class="btn btn-sm btn-icon-only" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <router-link :to="{ name: 'AdminSerieFichier', params: { slug:serie.slug, id:serie.id } }" class="dropdown-item">Gestion des fichiers</router-link>
                                    <router-link :to="{ name: 'AdminSerieFiche', params: { slug:serie.slug, id:serie.id } }" class="dropdown-item">Modifier</router-link>
                                    <a class="dropdown-item" @click="getDestroy(serie)" href="#">Supprimer</a>
                                </div>
                            </div>
                        </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>
<script>
    export default {
        data(){
            return {
                type:'all',
                team:'cnf',
                informations:'',
            }


        },
        computed: {
        },
        watch:{
            informations(){
                this.$parent.titre = "Gestion des projets"
            },

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
<style>
    .dark .thead-light th{
        background-color: #1c1c1c !important;
        color: white;
    }
    .light a{
        color: black;
    }
    a:hover{
        color: orange;
    }
</style>