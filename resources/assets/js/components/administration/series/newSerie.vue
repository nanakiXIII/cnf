<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="spinner-border text-warning float-right" role="status" v-if="options.sync == true">
                                    <span class="sr-only">Chargement ...</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div v-if="options.etape == 0">
                            <div class="row">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <label class="input-group-text" for="type">Type</label>
                                    </div>
                                    <select class="custom-select" id="type" v-model="type">
                                        <option value="Animes">Animes</option>
                                        <option value="Scantrad">Scantrad</option>
                                        <option value="Light-Novel">Light Novel</option>
                                        <option value="Visual-Novel">Visual Novel</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-secondary btn-lg btn-block" @click="etape('+', 2)">
                                        Manuellement
                                    </button>
                                </div>
                                <div class="col-md-6" v-if="type != 'Visual-Novel'">
                                    <button type="button" class="btn btn-outline-secondary btn-lg btn-block" @click="etape('+', 1)">
                                        Automatiquement
                                    </button>
                                </div>
                            </div>

                        </div> <!-- Choix de la méthode -->
                        <div v-if="options.etape == 1">
                            <form v-on:submit.prevent="getInfo(name)">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="Search" autofocus placeholder="Titre de la série" v-model="name">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success" type="submit" id="button-addon2">valider</button>
                                    </div>
                                </div>
                            </form>

                            <div class="row" v-if="informations != null">
                                <div class="card mb-3 mr-1" style="width: 540px; cursor: pointer;"  v-for="info in informations.data.Page.media" @click="getAnimeka(info)">
                                    <div class="row no-gutters border" style="height: 180px">
                                        <div class="col-md-3">
                                            <img :src="info.coverImage.large" height="180px" width="100%" class="card-img" alt="...">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title">{{info.title.romaji}}</h5>
                                                <p class="card-text" v-if="info.type == 'ANIME'">{{info.episodes}} Episodes</p>
                                                <p class="card-text" v-if="info.type == 'MANGA'">{{info.volumes}} Tomes</p>
                                                <p class="card-text" v-if="info.type == 'MANGA'">{{info.chapters}} Chapitres</p>
                                                <p class="card-text"><small class="text-muted">{{info.type}}</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> <!-- Recherche de la série -->
                        <form class="mt-3" v-on:submit.prevent="getSubmit">
                            <div v-if="options.etape == 2">
                                <div v-if="serie.bannerImage" class="row banner" :style="'background-image: url('+serie.bannerImage+');'">
                                    <div data-v-b475b770="" class="shadow"></div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-2 position-relative" v-bind:class="{ 'cover-wrap': serie.bannerImage }" v-if="serie.coverImage">
                                        <img :src="serie.coverImage.large" width="100%" >
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <div class="input-group mb-1" >
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Titre</span>
                                                </div>
                                                <input type="text" class="form-control" v-model="serie.title.romaji">
                                            </div>
                                            <div class="input-group mb-1" >
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Titre Original</span>
                                                </div>
                                                <input type="text" class="form-control" v-model="serie.title.english">
                                            </div>
                                            <div class="input-group mb-1" >
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Titre Alternatif</span>
                                            </div>
                                            <input type="text" class="form-control" v-model="serie.title.native">
                                        </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-1" v-if="serie.type == 'ANIME'">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Production | Studio</span>
                                                </div>
                                                <input type="text" class="form-control" :value="date | moment('MMMM YYYY')">
                                                <input type="text" class="form-control" v-model="serie.studios.nodes[0].name">
                                            </div>
                                            <div class="input-group mb-1" v-if="serie.type == 'MANGA'">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Date de parution</span>
                                                </div>
                                                <input type="text" class="form-control" :value="date | moment('MMMM YYYY')">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-1" v-if="serie.type == 'ANIME'">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Episodes</span>
                                                </div>
                                                <input type="number" class="form-control" v-model="serie.episodes" min="0" required>
                                            </div>
                                            <div class="input-group mb-1" v-if="serie.type == 'MANGA'">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Tomes</span>
                                                </div>
                                                <input type="number" class="form-control" v-model="serie.volumes" min="0" required>
                                            </div>
                                            <div class="input-group mb-1" v-if="serie.type == 'MANGA'">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Chapitres</span>
                                                </div>
                                                <input type="number" class="form-control" v-model="serie.chapters" min="0" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Synopsis</span>
                                                </div>
                                                <textarea class="form-control" rows="8" v-model="serie.synopsis"></textarea>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3" v-if="!serie.coverImage.large">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="imageLabel">Image</span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="imageForm" aria-describedby="imageLabel" @change="onFileChange">
                                                <label class="custom-file-label" for="imageForm">{{image.name}}</label>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3" v-if="!serie.bannerImage">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="bannerLabel">Bannière</span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="bannerForm" aria-describedby="bannerLabel" @change="onFileChange">
                                                <label class="custom-file-label" for="bannerForm">{{banner.name}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- info basic Titre etc ... -->
                            <div v-if="options.etape == 2">
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="statut">Statut</label>
                                                </div>
                                                <select class="custom-select" id="statut" v-model="serie.statut" required>
                                                    <option value="0">En Cours</option>
                                                    <option value="1">Terminé</option>
                                                    <option value="2">Abandonné</option>
                                                    <option value="3">Licencié</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="etat">Etat</label>
                                                </div>
                                                <select class="custom-select" id="etat" v-model="serie.etat" required>
                                                    <option value="0" selected>Hors Ligne</option>
                                                    <option value="1">En ligne</option>
                                                </select>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Staff</span>
                                                </div>
                                                <textarea class="form-control" rows="8" v-model="serie.staff"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <template v-for="g in serie.allGenres">
                                                <div class="col-lg-3 col-md-4 col-xs-6" v-if="serie.allGenres">
                                                    <label :for="'genre'+g.id" v-bind:class="[found(genre, g.id) ? 'btn btn-outline-success' : 'btn btn-outline-info']" class="btn text-center">{{ g.name }}
                                                        <input type="checkbox" :value="g.id" :id="'genre'+g.id" class="badgebox" v-model="genre">

                                                    </label>
                                                </div>
                                            </template>
                                        </div>

                                    </div>
                                </div>
                            </div><!-- info basic Statut Genre etc ... -->
                            <div v-if="options.etape == 3"><!-- Ecran de chargement ... -->
                                <div class="row mt-2">
                                    <div class="col-md-12">
                                        <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                                            <span class="sr-only">Chargement ...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" v-if="options.etape > 1 && options.etape != 4">
                                <div class="progress mb-3" v-if="options.pourcentage != 0">
                                    <div class="progress-bar bg-warning" role="progressbar" :style="'width:'+options.pourcentage +'%'" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="col-md-6" v-if="options.etape != 3">
                                    <button type="button" class="btn btn-outline-secondary btn-lg btn-block" @click="etape('-', 1)">
                                        Précèdent
                                    </button>
                                </div>
                                <div class="col-md-6" v-if="options.etape == 2">
                                    <button type="submit" class="btn btn-outline-success btn-lg btn-block" >
                                        Valider
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){

            return {
                options:{
                    etape:0,
                    pourcentage: 0,
                    sync:false,
                },
                genre:"",
                name:"",
                informations: null,
                pourcentage:0,
                serie: {title:{},coverImage:{large:null},bannerImage:null},
                detail:null,
                date : null,
                type:'Animes',
                image:{name:'Selectionner une image'},
                banner:{name:'Selectionner une image'},
                uploadPercentage:0,
                debugs:null
            }


        },
        props:['user'],
        computed: {

        },
        watch:{

        },
        methods: {
            getSubmit(e){
                this.options.etape++
                e.preventDefault();
                let currentObj = this;
                let formData = new FormData();
                formData.append('image', this.image);
                formData.append('banner', this.banner);
                formData.append('serie', JSON.stringify(this.serie));
                formData.append('genre', JSON.stringify(this.genre));
                formData.append('type', this.type);
                formData.append('user_id', this.user.id);
                axios.post('/api/administration/series', formData, {
                    onUploadProgress: function( progressEvent ) {
                        this.options.pourcentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                    }.bind(this)
                })
                    .then(function (response) {
                        console.log(response.data)
                        currentObj.debugs = response.data;
                        currentObj.$router.push({name: 'AdminSerie'})
                    })
                    .catch(function (error) {
                        currentObj.etape('-', 1)
                        currentObj.debugs = error
                    });
            },
            found: function (tab, element) {
                let response = tab.indexOf(element)
                if (response >= 0){
                    return true
                }
                else{
                    return false
                }
            },
            etape(signe, value){
                if (signe == '+'){
                    this.options.etape = this.options.etape + value
                }else{
                    this.options.etape = this.options.etape - value
                }
            },
            onFileChange: function onFileChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length) return;
                if(e.target.id == 'imageForm'){
                    this.image = files[0];
                    console.log('bonjour')
                }else{
                    this.banner = files[0];
                }
                this.createImage(files[0], e.target.id)
            },
            createImage: function createImage(file, id) {
                console.log(file)
                var image = new Image();
                var reader = new FileReader();
                var vm = this;

                reader.onload = function (e) {
                    if(id == 'imageForm') {
                        vm.serie.coverImage.large = e.target.result
                    }else{
                        vm.serie.bannerImage = e.target.result
                    }
                };
                reader.readAsDataURL(file);
            },
            getAnimeka(info){
                this.options.sync = true;
                this.serie = info;
                window.axios.post('/api/administration/series/informations', {info})
                    .then(response => {
                        this.detail = response.data;
                        this.etape('+',1)
                        this.serie.synopsis = response.data.synopsis
                        this.serie.statut = response.data.statut
                        this.serie.etat = response.data.etat
                        this.genre = response.data.genre
                        this.serie.allGenres = response.data.newGenre
                        this.date = new Date(info.startDate.year+'-'+info.startDate.month+'-'+info.startDate.day)
                        this.options.sync = false;
                    });

            },
            async getInfo(name) {
                this.options.sync = true;
                var query = `
                query ($id: Int, $page: Int, $perPage: Int, $search: String) {
                    Page (page: $page, perPage: $perPage) {
                        pageInfo {
                          total
                          currentPage
                          lastPage
                          hasNextPage
                          perPage
                        }
                    media (id: $id, search: $search) {
                        id
                        title {
                            romaji
                            english
                            native
                            },
                        type,
                        status,
                        season,
                        episodes,
                        chapters,
                        volumes,
                        duration,
                        bannerImage,
                        genres,
                        coverImage{
                            large
                            medium
                        },
                        startDate{
                                year,
                                month,
                                day
                                  },
                        studios{nodes{name}},
                    }
                }
            }`;
            var variables = {
                search: name,
                page: 1,
                perPage: 6
            };
            var url = 'https://graphql.anilist.co',
                options = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        query: query,
                        variables: variables
                    })
            };
            let response = await fetch(url, options);
            let data = await response.json();
            this.informations = data;
                this.options.sync = false;
            },
        },
        mounted(){
            this.$parent.titre = "Nouveau Projet"
        }
    }
    </script>