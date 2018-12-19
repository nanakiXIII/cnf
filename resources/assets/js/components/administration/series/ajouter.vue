<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="mb-0">
                                    <i class="fas fa-folder-plus"></i> Ajouter une série
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <template v-if="data == 'Ajouter'">
                            <ul class="chec-radio" v-if="!choix">
                                <li class="pz">
                                    <label class="radio-inline">
                                        <input type="radio" name="choix" class="pro-chx" value="Animeka" v-model="choix">
                                        <div class="clab">Animeka</div>
                                    </label>
                                </li>
                                <li class="pz">
                                    <label class="radio-inline">
                                        <input type="radio" name="choix" class="pro-chx" value="Nautiljon" v-model="choix">
                                        <div class="clab">Nautiljon</div>
                                    </label>
                                </li>
                                <li class="pz">
                                    <label class="radio-inline">
                                        <input type="radio" name="choix" class="pro-chx" value="Manuel" v-model="choix">
                                        <div class="clab">Manuellement</div>
                                    </label>
                                </li>
                            </ul>
                            <form v-if="choix == 'Animeka' || choix == 'Nautiljon'" v-on:submit.prevent="formulaire">
                                <div class="alert alert-danger" role="alert" v-if="erreurs">
                                    Une erreur est survenue<br>
                                    "{{ erreur }}"
                                </div>
                                <div class="form-group" >
                                    <label for="url">{{ choix }}</label>
                                    <input type="text" class="form-control" id="url" required :placeholder="placeholder" v-model="url">
                                </div>
                                <button type="button" @click="choix = ''; data = 'Ajouter'" class="btn btn-warning">Annuler</button>
                                <button type="submit" class="btn btn-success" v-if="!search">Recherche</button>
                                <span class="btn btn-outline-success" v-if="search">
                                Recherche en cours
                                <i class="fas fa-sync-alt rotation"></i>
                            </span>
                            </form>
                        </template>

                        <form v-if="data == 'formulaire' && sauvegarde == false" v-on:submit.prevent="formulaire" enctype="multipart/form-data">
                            <div class="alert alert-danger" role="alert" v-if="erreurs">
                                Une erreur est survenue<br>
                                "{{ erreur }}"
                            </div>
                            <pre v-if="debug">
                                {{ information }}
                            </pre>
                            <div class="row border pt-3 pb-3 mb-1">
                                <div class="col-md-12">
                                    Publication
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="publication" id="enligne" value="1" v-model="information.publication">
                                        <label class="form-check-label text-success" for="enligne">
                                            Visible
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="publication" id="offligne" value="0" v-model="information.publication">
                                        <label class="form-check-label text-danger" for="offligne">
                                            Non Visible
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1">
                                <div class="form-group col-md-6" v-if="information">
                                    <label for="titre">Titre</label>
                                    <input type="text" class="form-control" id="titre" v-model.trim="information.titre" required>
                                </div>
                                <div class="form-group col-md-6" v-if="information">
                                    <label for="titre_original">Titre Original</label>
                                    <input type="text" class="form-control" id="titre_original" v-model.trim="information.titre_original" required>
                                </div>
                                <div class="form-group col-md-6" v-if="information">
                                    <label for="titre_alternatif">Titre Alternatif</label>
                                    <input type="text" class="form-control" id="titre_alternatif" v-model.trim="information.titre_alternatif" required>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1">
                                <div class="form-group col-md-6" v-if="information">
                                    <label for="studio">Studio</label>
                                    <input type="text" class="form-control" id="studio" v-model.trim="information.studio" required>
                                </div>
                                <div class="form-group col-md-6" v-if="information">
                                    <label for="auteur">Auteur</label>
                                    <input type="text" class="form-control" id="auteur" v-model.trim="information.auteur" required>
                                </div>
                                <div class="form-group col-md-6" v-if="information">
                                    <label for="annee">Année de production</label>
                                    <input type="text" class="form-control" id="annee" v-model.trim="information.annee" required>
                                </div>
                            </div>
                            <div class="row border mb-1 ">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="synopsis">Synopsis</label>
                                        <textarea class="form-control" id="synopsis" v-model="information.synopsis" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row border mb-1 ">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="staff">Staff</label>
                                        <textarea class="form-control" id="staff" v-model="information.staff" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row border mb-1 ">
                                <div class="col-md-12">
                                    Etat de la série
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="etat" id="encours" value="0" v-model="information.etat" checked>
                                        <label class="form-check-label" for="encours">
                                            En cours
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="etat" id="termine" value="1" v-model="information.etat">
                                        <label class="form-check-label" for="termine">
                                            Terminé
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="etat" id="abandonne" value="2" v-model="information.etat">
                                        <label class="form-check-label" for="abandonne">
                                            Abandonné
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="etat" id="licence" value="3" v-model="information.etat">
                                        <label class="form-check-label" for="licence">
                                            Licencié
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1">
                                <div class="form-group col-md-12" v-if="information">
                                    <label for="type">Type de série</label>
                                    <select type="select" class="form-control" id="type" v-model="information.type" required>
                                        <option value="0" disabled selected>Selectionnez une option</option>
                                        <option value="Animes">Animés</option>
                                        <option value="Scantrad">Scantrad</option>
                                        <option value="Light-Novel">Light Novel</option>
                                        <option value="Visual-Novel">Visual Novel</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6" v-if="information.type == 'Animes'">
                                    <label for="episode">Episodes</label>
                                    <input type="number" class="form-control" id="episode" v-model="information.episode" min="0">
                                </div>
                                <div class="form-group col-md-6" v-if="information.type == 'Animes'">
                                    <label for="oav">OAVS</label>
                                    <input type="number" class="form-control" id="oav" v-model="information.oav" min="0">
                                </div>
                                <div class="form-group col-md-6" v-if="information.type == 'Animes'">
                                    <label for="films">Films</label>
                                    <input type="number" class="form-control" id="films" v-model="information.films" min="0">
                                </div>
                                <div class="form-group col-md-6" v-if="information.type == 'Animes'">
                                    <label for="bonus">Bonus</label>
                                    <input type="number" class="form-control" id="bonus" v-model="information.bonus" min="0">
                                </div>
                                <div class="form-group col-md-6" v-if="information.type == 'Scantrad'">
                                    <label for="scan">Tomes</label>
                                    <input type="number" class="form-control" id="scan" v-model="information.scan" min="0">
                                </div>
                                <div class="form-group col-md-6" v-if="information.type == 'Scantrad'">
                                    <label for="chapitre">Chapitres</label>
                                    <input type="number" class="form-control" id="chapitre" v-model="information.episode" min="0">
                                </div>
                                <div class="form-group col-md-6" v-if="information.type == 'Light-Novel'">
                                    <label for="ln">Tomes</label>
                                    <input type="number" class="form-control" id="ln" v-model="information.ln" min="0">
                                </div>
                                <div class="form-group col-md-6" v-if="information.type == 'Light-Novel'">
                                    <label for="lnChapitre">Chapitres</label>
                                    <input type="number" class="form-control" id="lnChapitre" v-model="information.episode" min="0">
                                </div>
                                <div class="form-group col-md-6" v-if="information.type == 'Visual-Novel'">
                                    <label for="vn">Visual Novel</label>
                                    <input type="number" class="form-control" id="vn" v-model="information.vn" min="0">
                                </div>
                            </div>
                            <div class="row border mb-1 " v-if="information.genres">
                                <div class="col-md-12 p-2 text-center">
                                   {{ information.genres }}
                                </div>
                            </div>
                            <div class="row border pt-3 mb-1">
                                <div class="col-md-12">
                                    Genres
                                </div>
                                <template v-for="g in genres">
                                    <div class="col-lg-3 col-md-4 col-xs-4" v-if="information.genre">
                                        <label :for="g.id" v-bind:class="[found(information.genre, g.id) ? 'btn-success' : 'btn-warning']" class="btn mr-2">{{ g.name }}
                                            <input type="checkbox" :value="g.id" :id="g.id" class="badgebox" v-model="information.genre">
                                            <span class="badge">&check;</span>
                                        </label>
                                    </div>
                                </template>
                            </div>
                            <div class="row border pt-3 pb-3 mb-1">
                                <div class="col-md-12">
                                    Choix de l'image
                                </div>
                                <div class="col-md-6 text-center" v-if="choix != 'Manuel'">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="imageChoix" id="manuel" value="manuel" v-model="information.imageChoix">
                                        <label class="form-check-label" for="manuel">
                                            Manuellement
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center" v-if="choix != 'Manuel'">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="imageChoix" id="auto" value="auto" v-model="information.imageChoix">
                                        <label class="form-check-label" for="auto">
                                            Automatiquement
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3" v-if="information.imageChoix == 'manuel'">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" ref="file" accept="image/*" v-on:change="handleFileUpload()">
                                        <label class="custom-file-label" for="image" >{{imageTitre}}</label>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3" v-if="information.imageChoix == 'auto'">
                                    <label v-for="i in information.image" class="col-md-4 mb-2">
                                        <input type="radio" name="image" :value="i.url" class="radio-img" v-model="information.imagecheck">
                                        <img :src="i.url" width="100%" >
                                    </label>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6 text-left">
                                    <button type="button" @click="choix = ''; data = 'Ajouter'" class="btn btn-warning">Annuler</button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" @click="action = 'newSerie'" class="btn btn-success">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                        <div v-if="sauvegarde == true" class="text-center">
                            <h1 v-if="validate == false">
                            <span class="btn btn-outline-success" v-if="sauvegarde">
                                Sauvegarde en cours
                                <i class="fas fa-sync-alt rotation"></i>
                            </span>
                            </h1>
                            <div class="alert alert-success" role="alert" v-if="validate == true">
                                La série {{ response.serie.titre }} a bien été ajoutée
                            </div>
                        </div>
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
                action:"",
                data:"Ajouter",
                choix:"",
                url:"",
                sauvegarde:false,
                search:false,
                validate:false,
                erreurs:"",
                information:{},
                debug:false,
                imageTitre: 'Selectionner une image',
                file:{}
            }


        },
        computed: {
            serie(){
                return this.$store.getters.getSeries
            },
            erreur(){
                return this.$store.getters.getErrorSerie
            },
            response(){
                return this.$store.getters.getReponseSerie
            },

        },
        watch:{
            choix(){
                if(this.choix == 'Animeka'){
                    this.placeholder = "https://www.animeka.com/animes/detail/..."
                    this.action = "Information"
                }
                else if(this.choix == 'Nautiljon'){
                    this.placeholder = "https://www.nautiljon.com/..."
                    this.action = "Information"
                }
                else if(this.choix == 'Manuel'){
                    this.data = 'formulaire'
                    this.information.etat = 0
                    this.information.imageChoix = 'manuel'
                    this.information.type = '0'
                    this.information.genre = []
                }

            },
            erreur(){
                this.erreurs = this.erreur
                this.search = false
                if (this.action == "Information"){
                    this.data = "Ajouter"
                }
            },
            response(){
                if (this.action == "Information"){
                    this.information = this.response
                    this.search = false;
                    this.data = 'formulaire'
                    this.genres =  this.response.newGenre

                }
                if (this.response.data == true && this.response.action == 'newSerie'){
                    setTimeout(function () { this.validate = true; }.bind(this), 1000)
                    setTimeout(function () { this.validate = false; this.sauvegarde = false; this.information = {}; this.choix = ""; this.data = 'Ajouter'; this.url = null }.bind(this), 3000)
                }
            },
            serie(){
                if (this.serie){
                    this.genres = this.serie.genre;
                }
            }
        },
        methods: {
            found: function (tab, element) {
                let response = tab.indexOf(element)
                if (response >= 0){
                    return true
                }
                else{
                    return false
                }
            },
            paginate(){
                const { data } = this
                this.$store.dispatch('SeriesRequest', {data});
            },
            handleFileUpload(){
                const fileInput = document.querySelector( '#image' );
                this.file = fileInput.files[0];
                this.imageTitre = this.$refs.file.files[0].name
                console.log(this.file)
            },
            formulaire() {

                //this.data = "Ajouter"
                this.erreurs = "";
                const { action, url, choix, file } = this;
                const { titre, titre_original, titre_alternatif, studio, auteur, annee, synopsis, staff, etat, type, episode, oav, films, bonus, ln, scan, vn, genre, imageChoix, imagecheck, publication  } = this.information
                if (action == "Information"){this.search = true}
                if(this.action == "newSerie"){this.sauvegarde = true}

                this.$store.dispatch('FormulaireSerieRequest', { action, url, choix, titre, titre_original, titre_alternatif, studio, auteur, annee, synopsis, staff, etat, type, episode, oav, films, bonus, ln, scan, vn, genre, imageChoix, imagecheck, file, publication})
                    .then(() => {

                    })
                    .catch(()=>{
                        console.log('erreur')
                    })
            }
        },
        mounted(){
            this.$parent.titre = "Ajouter une série"
            this.paginate()
        }
    }
</script>