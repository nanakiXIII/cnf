<template>
    <div class="container mt-5">
        <div v-if="information == ''">
            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                <div class="btn-group" role="group">
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
            </div>
            <div class="row mt-4 border bg-white relative" v-for="(serie, key) in series.series" v-if="type == 'all' || type == serie.type">
                <template v-if="serie">
                    <div class="triangle">
                        <p>
                            <i class="fas fa-gamepad" v-if="serie.type == 'Visual-Novel'"></i>
                            <i class="fas fa-book-open" v-if="serie.type == 'Light-Novel'"></i>
                            <i class="fas fa-paint-brush" v-if="serie.type == 'Scantrad'"></i>
                            <i class="fas fa-video" v-if="serie.type == 'Animes'"></i>
                        </p>
                    </div>
                    <div class="col-md-4 img-news row">
                        <img :src="serie.image">
                    </div>
                    <div class="col-md-8 ">
                        <div class="card-block px-1">
                            <h4 class="card-title pb-1 pt-3 title">
                                <b>{{ serie.titre }}</b>
                            </h4>
                            <p class="card-text bg-grey col-md-12 p-3 mb-3">
                                <span class='info-tooltip'>Etat</span>
                                <span class="text-primary" v-if="serie.etat == 0">En Cours</span>
                                <span class="text-success" v-if="serie.etat == 1">Terminé</span>
                                <span class="text-warning" v-if="serie.etat == 2">Abandonné</span>
                                <span class="text-danger" v-if="serie.etat == 3">Licencié</span><br>
                                <span class='info-tooltip'>Type</span> {{ serie.type }}<br>
                                <span class='info-tooltip'>Publication</span>
                                <span class="text-success" v-if="serie.publication == 1">Visible</span>
                                <span class="text-danger" v-if="serie.publication == 0">Non Visible</span>
                                <br>
                                <span class='info-tooltip'>Genres</span>
                                <span v-for="genre in serie.genres">
                                {{ genre.name}}
                            </span>
                            </p>
                            <div class="btn-group btn-group-justified pull-right" role="group" aria-label="...">
                                <div class="btn-group" role="group">
                                    <router-link :to="{ name: 'serieDetail', params: { type:serie.type ,slug: serie.slug} }">
                                        <button type="button"
                                                class="btn btn-success">
                                            <i class="fas fa-globe"></i> Voir
                                        </button>
                                    </router-link>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                            class="btn btn-info"
                                            @click="donnee(key)">
                                        <i class="fas fa-sync-alt"></i> Modifier
                                    </button>
                                </div>
                                <div class="btn-group" role="group">
                                    <router-link :to="{ name: 'AdminSerieFichier', params: { type:serie.type ,slug: serie.slug} }">
                                        <button type="button"
                                                class="btn btn-info">
                                            <i class="fas fa-file"></i> Fichiers
                                        </button>
                                    </router-link>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                            class="btn btn-danger"
                                            @click="modal(serie.id, serie.titre, key)">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <div v-else="">
            <div class="card">
                <div class="card-body">
                    <form v-on:submit.prevent="formulaire" enctype="multipart/form-data" v-if="sauvegarde == false">
                        <div class="alert alert-danger" role="alert" v-if="erreurs">
                            Une erreur est survenue<br>
                            "{{ erreur }}"
                        </div>
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
                        <div class="row border pt-3 mb-1">
                            <div class="col-md-12">
                                Genres
                            </div>
                            <template v-for="g in series.genre">
                                <div class="col-lg-3 col-md-4 col-xs-4" v-if="information.genreID">
                                    <label :for="g.id" v-bind:class="[found(information.genreID, g.id) ? 'btn-success' : 'btn-warning']" class="btn mr-2">{{ g.name }}
                                        <input type="checkbox" :value="g.id" :id="g.id" class="badgebox" v-model="information.genreID">
                                        <span class="badge">&check;</span>
                                    </label>
                                </div>
                            </template>
                        </div>
                        <div class="row border pt-3 pb-3 mb-1">
                            <div class="col-md-12">
                                Choix de l'image
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="imageChoix" id="manuel" value="manuel" v-model="information.imageChoix">
                                    <label class="form-check-label" for="manuel">
                                        Manuellement
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="imageChoix" id="auto" value="auto" v-model="information.imageChoix">
                                    <label class="form-check-label" for="auto">
                                        Automatiquement
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="imageChoix" id="non" value="non" v-model="information.imageChoix">
                                    <label class="form-check-label" for="non">
                                        Non
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
                                <label v-for="i in images" class="col-md-4 mb-2">
                                    <input type="radio" name="image" :value="i.url" class="radio-img" v-model="information.imagecheck">
                                    <img :src="i.url" width="100%" >
                                </label>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6 text-left">
                                <button type="button" @click="information = ''" class="btn btn-warning">Annuler</button>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                            </div>
                        </div>
                    </form>
                    <div v-if="sauvegarde == true" class="text-center">
                        <h1>
                            <span class="btn btn-outline-success" v-if="sauvegarde">
                                Sauvegarde en cours
                                <i class="fas fa-sync-alt rotation"></i>
                            </span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-vue" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Supprimer {{ titre }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 text-center">
                            Etes vous sur de supprimer

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-outline-danger" @click="supprimer(true)">
                                    <i class="fas fa-trash"></i> Oui
                                </button>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-outline-success" @click="supprimer(false)">
                                    Hum ... Non
                                </button>
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
                action:"modifierSerie",
                data:"Ajouter",
                type:'all',
                information:'',
                erreurs:'',
                imageTitre:'Selectionner une image',
                option:'',
                key:"",
                sauvegarde:false,
                idDelete:"",
                titre:""
            }


        },
        computed: {
            series(){
                return this.$store.getters.getSeries
            },
            erreur(){
                return this.$store.getters.getErrorSerie
            },
            images(){
                return this.$store.getters.getSeriesInfo
            },
            response(){
                return this.$store.getters.getReponseSerie
            },
        },
        watch:{
            erreur(){
                this.erreurs = this.erreur
            },
            response(){
                if (this.response.data == true && this.response.action == 'modifier'){
                    this.series.series[this.key] = this.response.serie
                    setTimeout(function () { this.sauvegarde = false; this.information = ''; }.bind(this), 1000)
                }
                if (this.response.data == true && this.response.action == 'delete'){
                    this.action= "modifierSerie"
                    this.sauvegarde = false;
                    this.$delete(this.series.series, this.key)
                    $('#modal-vue').modal('hide');
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
            donnee(key){
                this.key = key;
                this.information = this.series.series[key]
                this.data = 'image'
                this.option = this.information.titre
                const { data, option } = this
                this.$store.dispatch('SeriesRequest', {data, option});
            },
            handleFileUpload(){
                const fileInput = document.querySelector( '#image' );
                this.file = fileInput.files[0];
                this.imageTitre = this.$refs.file.files[0].name
                console.log(this.file)
            },
            modal(id, titre, key){
                $('#modal-vue').modal('show');
                this.idDelete = id;
                this.titre = titre;
                this.key = key
            },
            supprimer(etat){
                if(etat){
                    this.action = "delete"
                    this.formulaire()

                }else{
                    $('#modal-vue').modal('hide');
                }

            },
            formulaire() {
                this.erreurs = "";
                const { action, file, idDelete } = this;
                const { id, titre, titre_original, titre_alternatif, studio, auteur, annee, synopsis, staff, etat, type, episode, oav, films, bonus, ln, scan, vn, genreID, imageChoix, imagecheck  } = this.information
                this.sauvegarde = true
                this.$store.dispatch('FormulaireSerieRequest', {idDelete, id, action, titre, titre_original, titre_alternatif, studio, auteur, annee, synopsis, staff, etat, type, episode, oav, films, bonus, ln, scan, vn, genreID, imageChoix, imagecheck, file})
                    .then(() => {
                        if (!this.erreurs){


                        }else{

                        }


                    })
                    .catch(()=>{
                        console.log('erreur')
                    })
            }
        },
        mounted(){
            this.$parent.titre = "Listes des séries"
            this.paginate()
        }
    }
</script>