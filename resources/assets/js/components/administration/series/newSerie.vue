<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="bg-white p-3 border">
                    <nav class="nav nav-pills nav-justified border">
                        <a @click="serie.type = 'ANIME'" v-bind:class="{'router-link-exact-active router-link-active':(serie.type === 'ANIME')}" class="nav-item nav-link text-truncate cursor">
                            <b> Animés</b>
                        </a>
                        <a @click="serie.type = 'MANGA'" v-bind:class="{'router-link-exact-active router-link-active':(serie.type === 'MANGA')}" class="nav-item nav-link text-truncate cursor">
                            <b> Scantrad</b>
                        </a>
                        <a @click="serie.type = 'Light-Novel'" v-bind:class="{'router-link-exact-active router-link-active':(serie.type === 'Light-Novel')}" class="nav-item nav-link text-truncate cursor">
                            <b> Light Novel</b>
                        </a>
                        <a @click="serie.type = 'Visual-Novel'" v-bind:class="{'router-link-exact-active router-link-active':(serie.type === 'Visual-Novel')}" class="nav-item nav-link text-truncate cursor">
                            <b> Visual Novel</b>
                        </a>
                    </nav>
                    <form v-on:submit.prevent="getInfo(name)" class="mt-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="Search" autofocus placeholder="Chercher une série" v-model="name">
                            <div class="input-group-append">
                                <button class="btn btn-outline-colorise" type="submit" id="valider" v-bind:class="{'disable':(options.sync == true)}" >
                                    <span class="spinner-border spinner-border-sm" v-if="options.sync == true" role="status" aria-hidden="true"></span>
                                    <span v-if="options.sync == false" >Recherche</span>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="mt-3" v-if="informations != null && options.choix == false">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush table-hover">
                                <tbody>
                                <tr v-for="info in informations.data.Page.media" @click="getAnime(info)" class="cursor">
                                    <th scope="row">
                                        <div class="media align-items-center">
                                            <img alt="Image placeholder" height="50px" width="50px" class="rounded-circle mr-4 ml-3 shadow-sm" :src="info.coverImage.large">
                                            <div class="media-body">
                                                <span class="mb-0 text-sm text-break">{{info.title.romaji}}</span>
                                            </div>
                                        </div>
                                    </th>
                                    <td class="text-center">
                                        <span class="text-primary" v-if="serie.etat == 0">En Cours</span>
                                        <span class="text-success" v-if="serie.etat == 1">Terminé</span>
                                        <span class="text-warning" v-if="serie.etat == 2">Abandonné</span>
                                        <span class="text-danger" v-if="serie.etat == 3">Licencié</span>
                                    </td>
                                    <td class="text-center">
                                        {{info.type}}
                                    </td>
                                    <td class="text-center">
                                        <p class="card-text" v-if="info.type == 'ANIME'">{{info.episodes}} Episodes</p>
                                        <p class="card-text" v-if="info.type == 'MANGA'">{{info.volumes}} Tomes</p>

                                    </td>
                                    <td>
                                        <p class="card-text" v-if="info.type == 'MANGA'">{{info.chapters}} Chapitres</p>
                                    </td>
                                    <td>
                                        {{ info.startDate.year}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center cursor" v-if="informations != null && options.choix == true" @click="options.choix = false">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="text-center cursor" v-if="informations != null && options.choix == false && serie.title.romaji " @click="options.choix = true">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div> <!-- Recherche + choix du type -->
                <form v-on:submit.prevent="postSerie">
                    <div class="bg-white p-3 mt-3 border relative" id="titres">
                        <div class="overlay" v-if="options.sync"></div>
                        <div class="form-group">
                            <label for="titre">Titre</label>
                            <input type="text" class="form-control" id="titre" v-model="serie.title.romaji">
                        </div>
                        <div class="form-group">
                            <label for="titre_original">Titre Original</label>
                            <input type="text" class="form-control" id="titre_original" v-model="serie.title.english">
                        </div>
                        <div class="form-group">
                            <label for="titre_alternatif">Titre Alternatif</label>
                            <input type="text" class="form-control" id="titre_alternatif" v-model="serie.title.native">
                        </div>
                    </div> <!-- input des titres -->
                    <div class="bg-white p-3 mt-3 border relative" id="informations">
                        <div class="overlay" v-if="options.sync"></div>
                        <div class="form-group">
                            <label for="date">Date de parution</label>
                            <input type="text" class="form-control" id="date" :value="date | moment('MMMM YYYY')">
                        </div>
                        <div class="form-group" v-if="serie.type == 'ANIME'">
                            <label for="studio">Studio</label>
                            <input type="text" class="form-control" id="studio" v-model="serie.studios.nodes[0].name">
                        </div>
                        <div class="form-group">
                            <label for="Statut">Statut </label>
                            <select class="form-control" id="Statut" v-model="serie.statut">
                                <option value="0">En Cours</option>
                                <option value="1">Terminé</option>
                                <option value="2">Abandonné</option>
                                <option value="3">Licencié</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="visible">Visible </label>
                            <select class="form-control" id="visible" v-model="serie.publication">
                                <option value="0" class="text-danger">Non Visible</option>
                                <option value="1" class="text-success">Visible</option>
                            </select>
                        </div>
                    </div> <!-- informations -->
                    <div class="bg-white p-3 mt-3 border relative" id="Fichiers">
                        <div class="overlay" v-if="options.sync"></div>
                        <div class="form-group" v-if="serie.type == 'ANIME'">
                            <label for="episodes">Episodes</label>
                            <input type="number" class="form-control" id="episodes" min="0" v-model="serie.episodes">
                        </div>
                        <div class="form-group" v-if="serie.type == 'MANGA' || serie.type == 'Light-Novel'">
                            <label for="tomes">Tomes</label>
                            <input type="number" class="form-control" id="tomes" min="0" v-model="serie.volumes">
                        </div>
                        <div class="form-group" v-if="serie.type == 'MANGA' || serie.type == 'Light-Novel'">
                            <label for="chapitres">Chapites</label>
                            <input type="number" class="form-control" id="chapitres" v-model="serie.chapters">
                        </div>
                    </div> <!-- input des fichiers -->
                    <div class="bg-white p-3 mt-3 border relative" id="Images">
                        <div class="overlay" v-if="options.sync"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="banniere">Bannière</label>
                                    <input type="file" class="form-control-file" id="banniere" @change="onFileChange">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <img :src="serie.bannerImage" alt="" width="100%">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input type="file" class="form-control-file" id="image" @change="onFileChange">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <img :src="serie.coverImage.medium" alt="" style="max-height: 100px;">
                            </div>
                        </div>

                    </div> <!-- input des images -->
                    <div class="bg-white p-3 mt-3 border relative" id="nautilijon">
                        <form v-on:submit.prevent="getNauti(options.url)" class="mt-3">
                            <label for="nautili">Nautilijon</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="Nautilijon" autofocus placeholder="https://www.nautiljon.com/an..." v-model="options.url" id="nautili" v-bind:class="{'is-invalid':(options.nautilijon == 1),'is-valid':(options.nautilijon == 2)}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-colorise" type="submit" id="validerNautilijon" v-bind:class="{'disable':(options.sync == true)}" >
                                        <span class="spinner-border spinner-border-sm" v-if="options.sync == true" role="status" aria-hidden="true"></span>
                                        <span v-if="options.sync == false" >Recherche</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div> <!-- Cherche Nautilijon -->
                    <div class="bg-white p-3 mt-3 border relative" >
                        <div class="overlay" v-if="options.sync"></div>
                        <div class="form-group">
                            <label for="synopsis">Synopsis</label>
                            <textarea class="form-control" id="synopsis" rows="6" v-model="serie.synopsis"></textarea>
                        </div>
                    </div> <!-- Synopsis -->
                    <div class="bg-white p-3 mt-3 border relative" id="genres">
                        <div class="overlay" v-if="options.sync"></div>
                        <div class="form-group row">
                            <li class="list-group-item col-md-3" v-for="g in genres">
                                {{g.name}}
                                <label class="switch ">
                                    <input type="checkbox" class="success" :value="g.id" name="genres[]" v-model="serie.genres">
                                    <span class="slider round"></span>
                                </label>
                            </li>
                        </div>
                    </div> <!-- genres -->
                    <div class="bg-white p-3 mt-3 border relative" >
                        <div class="form-group">
                            <label for="staff">Equipe du projet</label>
                            <textarea class="form-control" id="staff" rows="6" v-model="serie.staff"></textarea>
                        </div>
                    </div> <!-- Staff -->
                    <div class="bg-white mt-3 border p-3">
                        <nav class="nav nav-pills nav-justified border">
                            <a @click="team = 'cnf'" v-bind:class="{'router-link-exact-active router-link-active':(team === 'cnf')}" class="nav-item nav-link text-truncate cursor">
                                <b>Chuushin</b>
                            </a>
                            <a @click="team = 'set'" v-bind:class="{'router-link-exact-active router-link-active':(team === 'set')}" class="nav-item nav-link text-truncate cursor">
                                <b>SeedTeam</b>
                            </a>
                        </nav>
                    </div> <!-- team -->
                    <div class="row">
                        <div class="col-md-12 mt-3" v-if="options.pourcentage != 0">
                            <div class="progress" >
                                <div class="progress-bar bg-warning" role="progressbar" :style="'width:'+options.pourcentage +'%'" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6 mt-3" >
                            <button type="submit" class="btn btn-outline-colorise btn-lg btn-block" >
                                Enregistrer
                            </button>
                        </div>
                    </div> <!-- submit -->
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                options:{
                    pourcentage: 0,
                    sync:false,
                    choix:false,
                    url:null,
                    nautilijon:0,
                },
                serie: {
                    title:{romaji:''},
                    statut:0,
                    publication:0,
                    type:'ANIME',
                    genres:[],
                    chapters: 0,
                    episodes:0,
                    volumes:0,
                    studios:{
                        nodes:[
                            {name:null}
                        ]
                    },
                    coverImage:{
                        large:null,
                        medium:null
                    },
                    bannerImage:null,
                },
                team:'cnf',
                error:null,
                genres:null,
                genre:[],
                name:"",
                informations: null,
                date : null,
                image:{name:'Selectionner une image'},
                banner:{name:'Selectionner une image'},
            }
        },
        methods: {
            getGenres(){
                axios.get('/api/administration/genres')
                    .then(response => {
                        this.genres = response.data
                    })
                .catch( error => {
                    this.$notify({
                        group: 'foo',
                        title: "Oups! Une erreur c'est produite",
                        type: 'error',
                        text: error
                    });
                })
            },
            postSerie(){
                this.serie.team = this.team
                let currentObj = this;
                let formData = new FormData();
                formData.append('image', this.image);
                formData.append('banniere', this.banner);
                formData.append('serie', JSON.stringify(this.serie));
                axios.post('/api/administration/series', formData, {
                    onUploadProgress: function( progressEvent ) {
                        this.options.pourcentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                    }.bind(this)
                })
                    .then(function (response) {
                        currentObj.$notify({
                            group: 'foo',
                            title: "Projet ajouté",
                            type: 'valid',
                            text: response.data.titre
                        });
                        currentObj.$router.push({name: 'AdminSerie'})
                    })
                    .catch(function (error) {
                        currentObj.$notify({
                            group: 'foo',
                            title: "Oups! Une erreur c'est produite",
                            type: 'error',
                            text: error
                        });
                    });
            },
            onFileChange: function onFileChange(e) {
                var files = e.target.files || e.dataTransfer.files;
                if (!files.length) return;
                if(e.target.id == 'image'){
                    this.image = files[0];
                }else{
                    this.banner = files[0];
                }
                this.createImage(files[0], e.target.id)
            },
            createImage: function createImage(file, id) {
                var image = new Image();
                var reader = new FileReader();
                var vm = this;
                reader.onload = function (e) {
                    if(id == 'image') {
                        vm.serie.coverImage.medium = e.target.result
                    }else{
                        vm.serie.bannerImage = e.target.result
                    }
                };
                reader.readAsDataURL(file);
            },
            getNauti(url){
                this.options.sync = true
                axios.post('/api/administration/series/informations',{url:url})
                    .then(response => {
                        this.error = null
                        this.options.sync = false
                        if (response.data.genres.length != 0){
                            this.serie.synopsis = response.data.synopsis
                            this.serie.genres = response.data.genres
                            this.getGenres()
                            this.options.nautilijon = 2
                        }else{
                            this.options.nautilijon = 1
                            this.$notify({
                                group: 'foo',
                                title: "Oups! Nautilijon",
                                type: 'error',
                                text: 'Merci de renseigné une adresse valide'
                            });
                        }

                    })
                    .catch( error => {
                        this.$notify({
                            group: 'foo',
                            title: "Oups! une erreur c'est produite",
                            type: 'error',
                            text: error
                        });
                        this.options.sync = false
                    })
            },
            getAnime(info){
                this.options.sync = true
                info.statut = this.serie.statut
                info.publication = this.serie.publication
                info.genres = this.serie.genres
                if (info.title.english == null){
                    info.title.english = info.title.romaji
                }
                if (!info.chapters){
                    info.chapters = this.serie.chapters
                }
                if (!info.episodes){
                    info.episodes = this.serie.episodes
                }
                if (!info.volumes){
                    info.volumes = this.serie.volumes
                }
                if (info.studios.nodes[0]=== undefined){
                    info.studios.nodes[0] = {name:null}
                }
                this.serie = info
                this.date = new Date(info.startDate.year+'-'+info.startDate.month+'-'+info.startDate.day)
                this.options.choix = true
                this.options.sync = false
                this.options.url = "https://www.nautiljon.com/"+info.type.toLowerCase()+'s/'+info.title.english.replaceAll(' ', '+').toLowerCase()+'.html'
                this.getNauti(this.options.url)
            },
            async getInfo(name) {
                this.options.choix =false
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
                perPage: 10
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
            this.getGenres()
        }
    }
    </script>
<style>
    .list-group-item{
        padding-left: 15px;
        padding-right: 15px;
        padding-bottom: 0!important;
        padding-top: 0!important;
        margin-bottom: 0!important;
        background-color: transparent!important;
        border: none!important;
    }
    .form-group{
        margin-bottom: 0!important;
    }
</style>