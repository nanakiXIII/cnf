<template>
    <div class="container mt-1">
        <div class="row mt-4 bg-white relative">
            <template v-if="series != ''">
                <div class="triangle">
                    <p>
                        <i class="fas fa-gamepad" v-if="series.type == 'Visual-Novel'"></i>
                        <i class="fas fa-book-open" v-if="series.type == 'Light-Novel'"></i>
                        <i class="fas fa-paint-brush" v-if="series.type == 'Scantrad'"></i>
                        <i class="fas fa-video" v-if="series.type == 'Animes'"></i>
                    </p>
                </div>
                <div class="col-md-3 img-news row">
                    <img :src="'/storage/images/images/'+series.image">
                </div>
                <div class="col-md-9 ">
                    <div class="card-block px-1">
                        <h4 class="card-title pb-1 pt-3 title">
                            <b>{{ series.titre }}</b>
                        </h4>
                        <p class="card-text bg-grey col-md-12 p-3 mb-3">
                            <span class='info-tooltip'>Etat</span>
                            <span class="text-primary" v-if="series.etat == 0">En Cours</span>
                            <span class="text-success" v-if="series.etat == 1">Terminé</span>
                            <span class="text-warning" v-if="series.etat == 2">Abandonné</span>
                            <span class="text-danger" v-if="series.etat == 3">Licencié</span><br>
                            <span class='info-tooltip'>Type</span> {{ series.type }}<br>
                            <span class='info-tooltip'>Publication</span>
                            <span class="text-success" v-if="series.publication == 1">Visible</span>
                            <span class="text-danger" v-if="series.publication == 0">Non Visible</span>
                            <br>
                            <span class='info-tooltip'>Genres</span>
                            <span v-for="genre in series.genres">
                                {{ genre.name}}
                            </span>
                        </p>
                    </div>
                </div>
            </template>
        </div>
        <div class="row mt-4 bg-white">
            <div class="col-md-6">
                <div class="card">
                    <div id="statistique">
                        <div class="card-body text-center">
                            <div class="spinner-grow" role="status" v-if="chargement == true">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div id="chart" ></div>

                        </div>
                    </div>
                </div>
            </div> <!-- Statistiques -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body ">
                        <div class="spinner-grow text-center" role="status" v-if="chargement == true">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <ul class="list-group list-group-flush" v-else="">
                            <li class="list-group-item">Dernière synchronisation : <b>{{ dateSync | moment('from') }}</b></li>
                            <li class="list-group-item text-center">
                                <button type="button" v-if="sync == false" class="btn btn-block btn-outline-info" @click="updateFtp()">
                                    Mettre à jour
                                </button>
                                <button type="button" v-if="sync == true" class="btn btn-block btn-outline-success">
                                    <i class="fas fa-sync-alt rotation "></i> Mise à jour en cours
                                </button>
                            </li>
                            <li class="list-group-item text-center"><b>Gestion des fichiers</b></li>
                            <li class="list-group-item">
                                <template v-if="series.type == 'Animes'">Saisons: </template>
                                <template v-if="series.type == 'Scantrad'|| series.type == 'Light-Novel'">Tomes: </template>
                                <template v-if="series.type == 'Visual-Novel'">Jeux: </template>
                                 {{ series.saisons.length }}
                            </li>
                            <li class="list-group-item">Fichiers: {{series.episodes }} / {{ series.episode }}</li>
                            <li class="list-group-item text-left">
                                <button type="button" class="btn btn-block btn-outline-info" @click="modal(true); action = 'nouvelleSaison'">
                                    Ajouter <b v-if="series.type == 'Animes'">Une Saison / Arc</b>
                                    <b v-if="series.type == 'Scantrad'|| series.type == 'Light-Novel'">Un Tome</b>
                                    <b v-if="series.type == 'Visual-Novel'">Un Jeux</b>
                                </button>
                            </li>

                        </ul>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 bg-white">
            <div class="col-md-12">
                <table class="table table-striped mt-3" v-for="(saison, key) in series.saisons">
                    <tbody>
                        <tr>
                            <th scope="row">{{saison.type}} {{saison.numero}}</th>
                            <td>{{ saison.name }}</td>
                            <td class="text-center">
                                <b class="text-success" v-if="saison.publication == 1">Visible</b>
                                <b class="text-danger" v-else="">Non Visible</b>
                            </td>
                            <td class="text-center">
                                {{ saison.episode }} Fichiers
                            </td>
                            <td class="text-right">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-outline-success btn-sm" @click="modalFichier(true); fichiers.idSaison = saison.id; fichiers.idSerie = series.id"><i class="fas fa-plus"></i></button>
                                    <button type="button" class="btn btn-outline-info btn-sm" @click="modal(true); information = saison; action='modifierSaison'"><i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" @click="getDestroySaison(saison)"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="episode in saison.episodes">
                            <td></td>
                            <td>
                                {{ episode.type }} {{ episode.numero }}: {{ episode.name }}
                            </td>
                            <td class="text-center">
                                <b class="text-success" v-if="episode.publication == 1">Visible</b>
                                <b class="text-danger" v-else="">Non Visible</b>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-success" v-if="episode.dvd != 'non'">DVD</span>
                                <span class="badge badge-pill badge-warning" v-else="">DVD</span>
                                <span class="badge badge-pill badge-success" v-if="episode.hd != 'non'">HD</span>
                                <span class="badge badge-pill badge-warning" v-else="">HD</span>
                                <span class="badge badge-pill badge-success" v-if="episode.fhd != 'non'">FHD</span>
                                <span class="badge badge-pill badge-warning" v-else="">FHD</span>
                                <span class="badge badge-pill badge-success" v-if="episode.streaming == '5'">Streaming</span>
                                <span class="badge badge-pill badge-warning" v-else="">Streaming</span>
                            </td>
                            <td class="text-right">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-outline-info btn-sm" @click="modalFichier(true); fichiers = episode">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" @click="getDestroy(episode)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="modal-vue" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <b v-if="series.type == 'Animes' && action == 'nouvelleSaison'">Nouvelle Saison / Arc</b>
                            <b v-if="series.type == 'Scantrad' || series.type == 'Light-Novel' && action == 'nouvelleSaison'">Nouveau Tome</b>
                            <b v-if="series.type == 'Visual-Novel' && action == 'nouvelleSaison'">Nouveau Jeux</b>
                            <b v-if="action == 'modifierSaison' || action == 'deleteSaison'">
                                {{ information.type }} {{ information.numero }}: {{ information.name }}
                            </b>
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form v-on:submit.prevent="formulaire(information)" enctype="multipart/form-data" v-if="sauvegarde == false && action != 'deleteSaison'" class="col-md-12">
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
                                        <option v-if="series.type == 'Animes'" value="Saison">Saison</option>
                                        <option v-if="series.type == 'Animes'" value="Arc">Arc</option>
                                        <option v-if="series.type == 'Scantrad' || series.type == 'Light-Novel'" value="Tome">Tome</option>
                                        <option v-if="series.type == 'Visual-Novel'" value="Jeux">Jeux</option>
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
                            <b v-if="!fichiers.id">Nouveau Fichier</b>
                            <b v-else="">{{fichiers.type }} {{ fichiers.numero}}: {{fichiers.name}}</b>
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
                                        <option v-if="series.type == 'Animes'" value="Episode">Episode</option>
                                        <option v-if="series.type == 'Animes'" value="OAV">OAV</option>
                                        <option v-if="series.type == 'Animes'" value="Film">Film</option>
                                        <option v-if="series.type == 'Animes'" value="Bonus">Bonus</option>
                                        <option v-if="series.type == 'Scantrad' || series.type == 'Light-Novel'" value="Chapitre">Chapitre</option>
                                        <option v-if="series.type == 'Visual-Novel'" value="Jeux">Jeux</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1" v-if="series.type == 'Animes'">
                                <div class="form-group col-md-12">
                                    <label for="fichierDVD">DVD</label>
                                    <select type="select" class="form-control" id="fichierDVD" v-model="fichiers.dvd">
                                        <option value="non">pas de fichier</option>
                                        <option :value="fichiers.dvd" v-if="fichiers.id">{{ fichiers.dvd }}</option>
                                        <option v-for="lf in listeFichier" :value="lf">{{ lf }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="fichierHD">HD</label>
                                    <select type="select" class="form-control" id="fichierHD" v-model="fichiers.hd">
                                        <option value="non">pas de fichier</option>
                                        <option :value="fichiers.hd" v-if="fichiers.id">{{ fichiers.hd }}</option>
                                        <option v-for="lf in listeFichier" :value="lf">{{ lf }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="fichierFHD">FHD</label>
                                    <select type="select" class="form-control" id="fichierFHD" v-model="fichiers.fhd">
                                        <option value="non">pas de fichier</option>
                                        <option :value="fichiers.fhd" v-if="fichiers.id">{{ fichiers.fhd }}</option>
                                        <option v-for="lf in listeFichier" :value="lf">{{ lf }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1" v-if="series.type == 'Scantrad'">
                                <div class="form-group col-md-12">
                                    <label for="exampleFormControlFile1">Archive</label>
                                    <input type="file" class="form-control-file" ref="file" id="exampleFormControlFile1" accept="application/zip" v-on:change="onFileChange">
                                    <small>Format Zip</small>
                                    <div class="progress mb-3" v-if="pourcentage != 0 && pourcentage != 'ok'">
                                        <div class="progress-bar bg-warning" role="progressbar" :style="'width:'+pourcentage +'%'" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div v-if="pourcentage == 'ok'">
                                        Upload effectuée
                                    </div>
                                </div>
                            </div>
                            <div class="row border mb-1 pt-1" v-if="series.type == 'Light-Novel'">
                                <div class="form-group col-md-12">
                                    <label for="pdf">Archive</label>
                                    <input type="file" class="form-control-file" ref="file" id="pdf" accept="application/pdf" v-on:change="onFileChange">
                                    <small>Format PDF</small>
                                    <div class="progress mb-3" v-if="pourcentage != 0 && pourcentage != 'ok'">
                                        <div class="progress-bar bg-warning" role="progressbar" :style="'width:'+pourcentage +'%'" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div v-if="pourcentage == 'ok'">
                                        Upload effectuée
                                    </div>
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
                series:"", //Information de la serie
                action:"nouvelleSaison",
                actionEpisode:'nouveauEpisode',
                chargement :true,
                statistique: '',
                information:{'name':'', 'numero':'0', 'type':'0', 'nosaison':'0', 'publication':'0', 'id': this.$route.params.id},
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
                file:'',
                pourcentage:0,
            }


        },

        watch:{
           response(){
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
        },
        methods: {
            ftp(){
                axios.get('/api/administration/fichier/ftp')
                    .then(response => {
                        this.dateSync = response.data.date.date
                        this.listeFichier = response.data.fichier
                    })
            },
            updateFtp(){
                this.sync = true
                axios.get('/api/administration/fichier/ftp/update')
                    .then(response => {
                        this.dateSync = response.data.date.date
                        this.sync = false
                    })
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
                this.pourcentage = 0;

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
                const {information } = this
                if(this.action == "nouvelleSaison"){
                    axios.post('/api/administration/saison/', information )
                        .then(response => {
                            this.modal(false);
                            this.validate = false;
                            this.sauvegarde = false;
                            this.getSerie(this.$route.params.slug, this.$route.params.id)
                            this.information = {'name':'', 'numero':'0', 'type':'0', 'nosaison':'0', 'publication':'0', 'id': this.$route.params.id}
                        })
                }else{
                    axios.put('/api/administration/saison/', information )
                        .then(response => {
                            this.modal(false);
                            this.validate = false;
                            this.sauvegarde = false;
                            this.getSerie(this.$route.params.slug, this.$route.params.id)
                            this.information = {'name':'', 'numero':'0', 'type':'0', 'nosaison':'0', 'publication':'0', 'id': this.$route.params.id}
                        })
                }

            },
            getDestroySaison(info){
                let options = {
                    okText: 'Confirmer',
                    cancelText: 'Fermer',
                    type:'hard',
                    verification: 'Supprimer',
                    verificationHelp: 'Tapez [+:verification] avant de confirmer',
                    message: 'Etes-vous sur de vouloir supprimer le '+info.type+' '+info.numero+': '+info.name,
                    clicksCount: 1,
                    backdropClose: true,
                };
                this.$dialog.confirm(options.message,options)
                    .then(() => {
                        axios.delete('/api/administration/saison/'+info.id)
                            .then(response =>{
                                this.getSerie(this.$route.params.slug, this.$route.params.id)
                            })
                    })
            },
            formulaireFichier() {
                if(this.fichiers.id){
                    this.formulaireModFichier()
                    return
                }
                this.sauvegarde = true;
                this.validate = true;
                const {fichiers } = this
                if(this.pourcentage == 'ok' || this.series.type == "Animes"){
                    axios.post('/api/administration/fichier', fichiers )
                        .then(response => {
                            this.modalFichier(false);
                            this.validate = false;
                            this.sauvegarde = false;
                            this.getSerie(this.series.slug, this.series.id)
                            this.ftp()
                            this.fichiers = {'idSaison':"", 'idSerie': this.series.id, 'name': "", 'numero': "", 'type':'', 'publication': 0, 'file':""}
                        })
                }
            },
            formulaireModFichier() {
                this.sauvegarde = true;
                this.validate = true;
                const {fichiers } = this
                if(this.pourcentage == 'ok' || this.pourcentage == 0 || this.series.type == "Animes"){
                    axios.put('/api/administration/fichier', fichiers )
                        .then(response => {
                            this.modalFichier(false);
                            this.validate = false;
                            this.sauvegarde = false;
                            this.getSerie(this.series.slug, this.series.id)
                            this.ftp()
                            this.fichiers = {'idSaison':"", 'idSerie': this.series.id, 'name': "", 'numero': "", 'type':'', 'publication': 0, 'file':""}
                        })
                }
            },
            getSerie(slug, id){
                axios.get('/api/administration/series/'+slug+'/'+id)
                    .then(response => {
                        this.series = response.data.data
                        this.$parent.titre = "Gestion des fichiers de "+this.series.titre
                    })

            },
            getStatistique(id){
                axios.get('/api/administration/statistique/serie/'+id)
                    .then(response => {
                        this.statistiques = response.data
                        this.chargement = false
                        this.affiche()
                    })
            },
            onFileChange: function onFileChange(e) {
                e.preventDefault();
                this.file = this.$refs.file.files[0]
                this.fichiers.file = this.$refs.file.files[0].name
                let formData = new FormData();
                formData.append('file', this.file);
                axios.post('/api/administration/fichier/archive', formData, {
                    onUploadProgress: function( progressEvent ) {
                        this.pourcentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                    }.bind(this)
                })
                    .then(response => {
                        this.pourcentage = 'ok';

                    })
            },
            getDestroy(info){
                let options = {
                    okText: 'Confirmer',
                    cancelText: 'Fermer',
                    type:'hard',
                    verification: 'Supprimer',
                    verificationHelp: 'Tapez [+:verification] avant de confirmer',
                    message: 'Etes-vous sur de vouloir supprimer '+info.type+' '+info.numero+': '+info.name,
                    clicksCount: 1,
                    backdropClose: true,
                };
                this.$dialog.confirm(options.message,options)
                    .then(() => {
                        axios.delete('/api/administration/fichier/'+info.id)
                            .then(response =>{
                                if(response.data.success == true){
                                    var slug =this.$route.params.slug;
                                    this.getSerie(slug, info.serie_id)
                                    this.ftp()
                                }else{

                                }
                            })
                    })
            }
        },
        mounted(){
            this.$parent.titre = "Chargement..."
            $('[data-toggle="tooltip"]').tooltip()
            let slug = this.$route.params.slug;
            let id = this.$route.params.id;
            this.information.id = id
            this.getSerie(slug, id)
            this.ftp();
            this.getStatistique(id)

        }
    }
</script>