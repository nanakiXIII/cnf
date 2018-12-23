<template>
    <div class="container mt-1">
        <div class="row mt-4 border bg-white relative">
            <template v-if="series.series">
                <div class="triangle">
                    <p>
                        <i class="fas fa-gamepad" v-if="series.type == 'Visual-Novel'"></i>
                        <i class="fas fa-book-open" v-if="series.series.type == 'Light-Novel'"></i>
                        <i class="fas fa-paint-brush" v-if="series.series.type == 'Scantrad'"></i>
                        <i class="fas fa-video" v-if="series.series.type == 'Animes'"></i>
                    </p>
                </div>
                <div class="col-md-4 img-news row">
                    <img :src="series.series.image">
                </div>
                <div class="col-md-8 ">
                    <div class="card-block px-1">
                        <h4 class="card-title pb-1 pt-3 title">
                            <b>{{ series.series.titre }}</b>
                        </h4>
                        <p class="card-text bg-grey col-md-12 p-3 mb-3">
                            <span class='info-tooltip'>Etat</span>
                            <span class="text-primary" v-if="series.series.etat == 0">En Cours</span>
                            <span class="text-success" v-if="series.series.etat == 1">Terminé</span>
                            <span class="text-warning" v-if="series.series.etat == 2">Abandonné</span>
                            <span class="text-danger" v-if="series.series.etat == 3">Licencié</span><br>
                            <span class='info-tooltip'>Type</span> {{ series.series.type }}<br>
                            <span class='info-tooltip'>Publication</span>
                            <span class="text-success" v-if="series.series.publication == 1">Visible</span>
                            <span class="text-danger" v-if="series.series.publication == 0">Non Visible</span>
                            <br>
                            <span class='info-tooltip'>Genres</span>
                            <span v-for="genre in series.series.genres">
                                {{ genre.name}}
                            </span>
                        </p>
                    </div>
                </div>
            </template>
        </div>
        <div class="alert alert-primary mt-4" role="alert">
            Synchronisation du ftp : <b>{{ dateSync | moment('from') }}</b>
            <div class="pull-right">
                <button type="button" v-if="sync == false" class="close" @click="updateFtp()">Mise à jour</button>
                <button type="button" v-if="sync == true" class="close text-success">
                    <i class="fas fa-sync-alt rotation "></i> Mise à jour en cours
                </button>
            </div>
        </div>
<pre>{{ fichiers }}</pre>
        <div class="mt-4 border">
            <div class="card">
                <div class="card-header cursor" data-toggle="collapse" data-target="#statistique" @click="affiche()">
                    <h5 class="mb-0 " ><i class="fas fa-chart-area"></i> Statistiques</h5>
                </div>
                <div id="statistique" class="collapse">
                    <div class="card-body text-center">

                        <div id="chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 border">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-folder"></i> Gestion des fichiers
                        <div class="pull-right cursor pl-3 text-center"@click="modal(true); action = 'nouvelleSaison'">
                            <i class="fas fa-folder-plus text-success"></i>
                        </div>
                    </h5>

                </div>
                <div class="card-content bg-white">
                    <div class="card" v-for="(saison, key) in series.saison">
                        <div class="card-header cursor" data-toggle="collapse" :data-target="'#saison'+saison.id">
                            <h5 class="mb-0 col-title" >
                                <i class="fas fa-eye text-success" title="Visible" v-if="saison.publication == '1'"></i>
                                <i class="fas fa-eye-slash text-danger" title="Non Visible" v-else></i>
                                {{saison.type}} {{saison.numero}}: {{ saison.name }}
                                <div class="pull-right">
                                    <i class="fas fa-plus text-success pl-3 cursor"  title="Ajouter un fichier" @click="modalFichier(true); fichiers.idSaison = saison.id"></i>
                                    <i class="fas fa-sync-alt text-info pl-3 cursor"  title="Modifier" @click="modal(true); information = saison; action='modifierSaison'"></i>
                                    <i class="fas fa-trash text-danger pl-3 cursor" title="Supprimer" @click="modal(true); information = saison; action='deleteSaison'; cle = key"></i>
                                </div>
                            </h5>
                        </div>
                        <div :id="'saison'+saison.id" class="collapse">
                            <div class="card-body" v-if="saison.episodes != ''">
                                <div class="media mb-2" v-for="episode in saison.episodes">
                                    <img class="align-self-center mr-3" :src="episode.image" alt="Chargement" width="150px">
                                    <div class="media-body">
                                        <h5 class="mt-0">{{ episode.type }} {{ episode.numero }}: {{ episode.name }}</h5>
                                        <p>
                                            <div class="btn-group ">
                                                <a href="" class="btn" v-bind:class="[ episode.downloaddvd ? 'btn-success' : 'btn-secondary']">DVD</a>
                                                <a href="" class="btn" v-bind:class="[ episode.downloadhd ? 'btn-success' : 'btn-secondary']">720P</a>
                                                <a href="" class="btn" v-bind:class="[ episode.downloadfhd ? 'btn-success' : 'btn-secondary']">1080P</a>
                                                <router-link :to="{name:'streaming', params:{saison: saison.id, episode:episode.id}}" class="btn" v-bind:class="[ episode.vue ? 'btn-success' : 'btn-secondary']">Streaming</router-link>
                                            </div>
                                            <small>
                                                <br>{{episode.downloads}} Téléchargement(s) | {{episode.vues}} vue(s)
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center" v-else-if="saison.episode != '' && saison.nosaison != true">
                                Pas de Fichier
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-vue" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <b v-if="type == 'Animes' && action == 'nouvelleSaison'">Nouvelle Saison / Arc</b>
                            <b v-if="type == 'Scantrad' || type == 'Light-Novel' && action == 'nouvelleSaison'">Nouveau Tome</b>
                            <b v-if="type == 'Visual-Novel' && action == 'nouvelleSaison'">Nouveau Jeux</b>
                            <b v-if="action == 'modifierSaison' || action == 'deleteSaison'">
                                {{ information.type }} {{ information.numero }}: {{ information.name }}
                            </b>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form v-on:submit.prevent="formulaire" enctype="multipart/form-data" v-if="sauvegarde == false && action != 'deleteSaison'" class="col-md-12">
                            <div class="row border mb-1 pt-1">
                                <div class="form-group col-md-6">
                                    <label for="name">Titre</label>
                                    <input type="text" class="form-control" id="name" v-model.trim="information.name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="numero">Numéro</label>
                                    <input type="number" min="0" class="form-control" id="numero" v-model.trim="information.numero" required>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1">
                                <div class="form-group col-md-12">
                                    <label for="type">Type</label>
                                    <select type="select" class="form-control" id="type" v-model="information.type" required>
                                        <option value="0" disabled selected>Selectionnez une option</option>
                                        <option v-if="type == 'Animes'" value="Saison">Saison</option>
                                        <option v-if="type == 'Animes'" value="Arc">Arc</option>
                                        <option v-if="type == 'Scantrad' || type == 'Light-Novel'" value="Tome">Tome</option>
                                        <option v-if="type == 'Visual-Novel'" value="Jeux">Jeux</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1">
                                <div class="col-md-12">
                                    Information de la saison
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="nosaison" id="saisonV" value="0" v-model="information.nosaison">
                                        <label class="form-check-label text-success" for="saisonV">
                                            Visible
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="nosaison" id="saisonN" value="1" v-model="information.nosaison">
                                        <label class="form-check-label text-danger" for="saisonN">
                                            Non Visible
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1">
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
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-info" @click="modal(false)">
                                        Annuler
                                    </button>

                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-outline-success" >
                                        <i class="fas fa-check"></i> Sauvegarder
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div v-if="action == 'deleteSaison' && sauvegarde == false" class="text-center">
                            Etes vous sur de supprimer
                            <div class="row mt-2">
                                <div class="col-md-6 text-left">
                                    <button type="button" class="btn btn-outline-danger" @click="formulaire()">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn btn-outline-info" @click="modal(false)">
                                        Annuler
                                    </button>

                                </div>
                            </div>

                        </div>
                        <div v-if="sauvegarde == true" class="text-center">
                            <span class="btn btn-outline-success" v-if="validate == false">
                                Sauvegarde en cours
                                <i class="fas fa-sync-alt rotation"></i>
                            </span>
                        </div>
                        <div class="alert alert-success" role="alert" v-if="validate == true">
                            Enregistrement effectué
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-fichier" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <b>Nouveau Fichier</b>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form v-on:submit.prevent="formulaireFichier" class="col-md-12">
                            <div class="row border mb-1 pt-1">
                                <div class="form-group col-md-6">
                                    <label for="fichiername">Titre</label>
                                    <input type="text" class="form-control" id="fichiername" v-model.trim="fichiers.name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="fichiernumero">Numéro</label>
                                    <input type="number" min="0" class="form-control" id="fichiernumero" v-model.trim="fichiers.numero" required>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1">
                                <div class="form-group col-md-12">
                                    <label for="fichiertype">Type</label>
                                    <select type="select" class="form-control" id="fichiertype" v-model="fichiers.type" required>
                                        <option value="0" disabled selected>Selectionnez une option</option>
                                        <option v-if="type == 'Animes'" value="Episode">Episode</option>
                                        <option v-if="type == 'Animes'" value="OAV">OAV</option>
                                        <option v-if="type == 'Animes'" value="Film">Film</option>
                                        <option v-if="type == 'Animes'" value="Bonus">Bonus</option>
                                        <option v-if="type == 'Scantrad' || type == 'Light-Novel'" value="Chapitre">Chapitre</option>
                                        <option v-if="type == 'Visual-Novel'" value="Jeux">Jeux</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1" v-if="type == 'Animes'">
                                <div class="form-group col-md-12">
                                    <label for="fichierDVD">DVD</label>
                                    <select type="select" class="form-control" id="fichierDVD" v-model="fichiers.dvd">
                                        <option value="non">pas de fichier</option>
                                        <option v-for="lf in listeFichier" :value="lf">{{ lf }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="fichierHD">HD</label>
                                    <select type="select" class="form-control" id="fichierHD" v-model="fichiers.hd">
                                        <option value="non">pas de fichier</option>
                                        <option v-for="lf in listeFichier" :value="lf">{{ lf }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="fichierFHD">FHD</label>
                                    <select type="select" class="form-control" id="fichierFHD" v-model="fichiers.fhd">
                                        <option value="non">pas de fichier</option>
                                        <option v-for="lf in listeFichier" :value="lf">{{ lf }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="streaming">Choix du fichier pour le streaming</label>
                                    <select type="select" class="form-control" id="streaming" v-model="fichiers.streaming" required>
                                        <option disabled selected>Selectionnez une option</option>
                                        <option value="dvd">DVD</option>
                                        <option value="hd">HD</option>
                                        <option value="fhd">FHD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1">
                                <div class="col-md-12">
                                    Publication
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="publication" id="fichierenligne" value="1" v-model="fichiers.publication" required>
                                        <label class="form-check-label text-success" for="fichierenligne">
                                            Visible
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="publication" id="fichieroffligne" value="0" v-model="fichiers.publication" required>
                                        <label class="form-check-label text-danger" for="fichieroffligne">
                                            Non Visible
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-info" @click="modalFichier(false)">
                                        Annuler
                                    </button>

                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-outline-success" >
                                        <i class="fas fa-check"></i> Sauvegarder
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
    import ApexCharts from 'apexcharts'
    export default {
        data(){
            return {
                action:"nouvelleSaison",
                actionEpisode:'nouveauEpisode',
                type :"",
                information:{'name':'', 'numero':'0', 'type':'0', 'nosaison':'0', 'publication':'0'},
                idSerie:'',
                sauvegarde:false,
                validate:false,
                cle:"",
                stat:false,
                fichiers:{},
                sync:false,
                dateSync:"",
                listeFichier:{},


                data:"Ajouter",
                type:'all',

                erreurs:'',
                imageTitre:'Selectionner une image',
                option:'',

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
            response(){
                return this.$store.getters.getReponseSerie
            },
            statistiques(){
                return this.$store.getters.getStatistique
            },
            fichier(){
                return this.$store.getters.getFichier
            },
        },
        watch:{
            erreur(){
                this.erreurs = this.erreur
            },
            series(){
                if(this.series.series){
                    this.$parent.titre = "Gestion des fichiers de "+ this.series.series.titre
                    this.idSerie = this.series.series.id
                }
            },
            response(){
                if (this.response.data == true && this.response.action == 'nouvelleSaison'){
                    this.series.saison.push(this.response.saison)
                    setTimeout(function () { this.validate = true; }.bind(this), 1000)
                    setTimeout(function () {
                        this.modal(false);
                        this.validate = false;
                        this.sauvegarde = false;
                        this.information = {'name':'', 'numero':'0', 'type':'0', 'nosaison':'0', 'publication':'0'}
                    }.bind(this), 3000)
                }
                if (this.response.data == true && (this.response.action == 'modifierSaison' || this.response.action == 'deleteSaison') ){
                    setTimeout(function () { this.validate = true; }.bind(this), 1000)
                    setTimeout(function () {
                        this.modal(false);
                        this.validate = false;
                        this.sauvegarde = false;
                        this.information = {'name':'', 'numero':'0', 'type':'0', 'nosaison':'0', 'publication':'0'}
                        if(this.response.action == 'deleteSaison'){
                            console.log(this.cle)
                            this.$delete(this.series.saison, this.cle)
                        }
                    }.bind(this), 3000)
                }
            },
            action(){
                if (this.action == 'nouvelleSaison'){
                    this.information={'name':'', 'numero':'0', 'type':'0', 'nosaison':'0', 'publication':'0'}
                }
            },
            fichier(){
                if (this.fichier.data == true && this.fichier.action == 'MiseAJour'){
                    setTimeout(function () { this.sync = false; }.bind(this), 1000)
                    this.dateSync = this.fichier.date.date
                    this.ftp()
                }
                if (this.fichier.data == true && this.fichier.action == 'ListeFichier'){
                    this.dateSync = this.fichier.date.date
                    if(this.type == 'Animes'){
                        this.listeFichier = this.fichier.fichier
                    }

                }
            }
        },
        methods: {
            ftp(){
                let ftp = 'ftp'
                this.$store.dispatch('FichierRequest', {ftp});
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
            modal(etat){
                if(etat){
                    $('#modal-vue').modal('show');
                }else{
                    $('#modal-vue').modal('hide');
                }

            },
            modalFichier(etat){
                if(etat){
                    $('#modal-fichier').modal('show');
                }else{
                    $('#modal-fichier').modal('hide');
                }

            },
            updateFtp(){
                this.sync = true
                let ftp = 'MiseAJour'
                this.$store.dispatch('FichierRequest', {ftp});
            },
            affiche(){
                var option =  {
                    chart: {
                        height: 350,
                        type: 'area',
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    series: [{
                        name: 'Telechargement',
                        data: this.statistiques.download
                    }, {
                        name: 'Visionnage',
                        data: this.statistiques.vues
                    }],

                    xaxis: {
                        type: 'category',
                        categories: this.statistiques.date,
                    },
                    tooltip: {
                        x: {
                            format: 'dd/MM/yy HH:mm'
                        },
                    }
                }
                if (this.stat == false){
                    this.stat = true
                    var chart = new ApexCharts(
                        this.$el.querySelector('#chart'),
                        option
                    );
                    setTimeout(function () { chart.render(); }.bind(chart), 500)
                }


            },
            formulaire() {
                this.erreurs = "";
                const { action, idSerie } = this;
                const { name, numero, publication, nosaison, type, id  } = this.information
                this.sauvegarde = true
                this.$store.dispatch('FormulaireSerieRequest', {action, idSerie, name, numero, publication, nosaison, type, id})
                    .then(() => {
                    })
                    .catch(()=>{
                        console.log('erreur')
                    })
            },
            formulaireFichier() {
                this.erreurs = "";
                const { action, actionEpisode, idSerie } = this;
                const { streaming, name, numero, publication, dvd, hd, fhd, idSaison, type  } = this.fichiers
                this.sauvegarde = true
                this.$store.dispatch('FormulaireFichierRequest', {action, actionEpisode, idSerie, streaming, name, numero, publication, dvd, hd, fhd, idSaison, type})
                    .then(() => {
                    })
                    .catch(()=>{
                        console.log('erreur')
                    })
            }
        },
        mounted(){

            $('[data-toggle="tooltip"]').tooltip()
            let type = this.$route.params.type;
            this.type = type;
            let slug = this.$route.params.slug;
            let data = "detail"
            this.$store.dispatch('SeriesRequest', {type, slug, data});
            data = 'serie';
            this.$store.dispatch('StatistiqueRequest', {type, slug, data});
            this.ftp();
        }
    }
</script>