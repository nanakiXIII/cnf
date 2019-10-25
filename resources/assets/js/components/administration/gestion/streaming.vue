<template>
    <div>
        <div class="container p-4 mt-3" v-if="info == true">
            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                <div class="btn-group" role="group">
                    <button type="button"
                            class="btn btn-default"
                            v-bind:class="{'colorise':(type === 'all')}"
                            @click="type = 'all'"
                    >
                        All
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button"
                            class="btn btn-default"
                            v-bind:class="{'colorise':(type === '5')}"
                            @click="type = '5'"
                    >
                        5
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button"
                            class="btn btn-default"
                            v-bind:class="{'colorise':(type === '4')}"
                            @click="type = '4'"
                    >
                        4
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button"
                            class="btn btn-default"
                            v-bind:class="{'colorise':(type === '3')}"
                            @click="type = '3'"
                    >
                        3
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button"
                            class="btn btn-default"
                            v-bind:class="{'colorise':(type === '2')}"
                            @click="type = '2'"
                    >
                        2
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button"
                            class="btn btn-default"
                            v-bind:class="{'colorise':(type === '1')}"
                            @click="type = '1'"
                    >
                        1
                    </button>
                </div>
                <div class="btn-group" role="group">
                    <button type="button"
                            class="btn btn-default"
                            v-bind:class="{'colorise':(type === '0')}"
                            @click="type = '0'"
                    >
                        0
                    </button>
                </div>
            </div> <!-- Choix du type -->
            <div class="card mb-3" style="max-width: 100%;" v-for="d in data" v-if="d.etat == type || type == 'all'">
                <div class="row no-gutters">
                    <div class="col-md-1">
                        <img :src="d.serie.image" class="card-img" width="100%" v-if="d.serie != null">
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <h5 class="card-title" v-if="d.serie != null">{{ d.serie.titre}} {{d.saison.type}} {{d.saison.numero}} {{d.type}} {{d.numero}}</h5>
                            <div class="card-text">
                                <div class="row" v-if="d.type != 'Chapitre'">
                                    <div class="col-md-6">
                                        <ul class="list-group">
                                            <li class="list-group-item" v-bind:class="{ 'list-group-item-success': d.etat >= '2', 'list-group-item-warning': d.etat == '1' }">
                                                Téléchargement
                                            </li>
                                            <li class="list-group-item" v-bind:class="{ 'list-group-item-success': d.etat >= '4', 'list-group-item-warning': d.etat == '3' }">
                                                Encodage
                                            </li>
                                            <li class="list-group-item" v-bind:class="{ 'list-group-item-success': d.image != 'noImage.jpg'}">
                                                Images
                                            </li>
                                            <li class="list-group-item" v-bind:class="{ 'list-group-item-success': d.original == false}">
                                                Vidéo original
                                            </li>
                                            <li class="list-group-item" v-bind:class="{ 'list-group-item-success': d.etat >= '4' }">
                                                Disponible
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-colorise dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Options {{d.etat}}
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#" @click="formulaire(d,'0')">Téléchargement</a>
                                                <a class="dropdown-item" href="#" @click="formulaire(d,'2')">Encodage</a>
                                                <a class="dropdown-item" href="#" @click="formulaire(d,'4')">Image</a>
                                                <a class="dropdown-item" href="#" @click="formulaire(d,'5')">Supprimer video original</a>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" @click="serie = d">
                                            Test du streaming
                                        </button>
                                    </div>
                                </div>
                                <div class="row" v-if="d.type == 'Chapitre'">
                                    <div class="col-md-6">
                                        <ul class="list-group">
                                            <li class="list-group-item" v-bind:class="{ 'list-group-item-success': d.image != 'noImage.jpg' }">
                                                Images
                                            </li>
                                            <li class="list-group-item" v-bind:class="{ 'list-group-item-success': d.lecture != '0'}">
                                                Lecture
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-colorise dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Options {{d.etat}}
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#" @click="formulaire(d,'0')">Extract</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" v-show="info == false">
            <div class="row mt-2">
                <div class="col-md-12 text-center">
                    <div class="spinner-border mt-5" style="width: 6rem; height: 6rem;" role="status">
                        <span class="sr-only">Chargement ...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="serie != null">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{serie.name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <video :src="serie.streaming" controls width="100%"></video>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
    import ApexCharts from 'apexcharts'
    export default {
        data(){
            return {
                type:'all',
                data:null,
                info:false,
                serie:null,
                visible : false,
            }


        },
        watch:{

        },
        computed: {
            user(){
                return this.$store.getters.getProfile
            },
        },
        methods: {
            formulaire(data, etat) {
                this.info = false
                const id = data.id
                axios.put('/api/administration/gestion/fichiers', {id, etat})
                    .then(response =>{
                        if(response.data == 'ok'){
                            this.getInfo()
                        }else{
                            this.info = true
                        }
                    })
            },
            getInfo(){
                axios.get('/api/administration/gestion/fichiers')
                    .then(response => {
                        this.data = response.data.data
                        this.info = true
                        this.$parent.titre = "Gestion des fichiers"
                    })

            },


        },
        mounted(){
            this.$parent.titre = "Chargements ..."
            this.getInfo()
        }
    }
</script>