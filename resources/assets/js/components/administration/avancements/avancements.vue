<template>
    <div>
        <div class="container bg-white p-4 mt-3" v-if="visible == true">
            <form v-on:submit.prevent="formulaire()">
                <div class="form-row">
                    <div class="form-group col-md-4" v-if="!information.id">
                        <label for="serie">Serie</label>
                        <select id="serie" class="form-control" v-model="information.serie" required>
                            <option v-for="s in serie" :value="s">[{{s.type}}] {{ s.titre }}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4" v-if="!information.id">
                        <label for="saison">Saison</label>
                        <select id="saison" class="form-control" v-model="information.saison" v-if="information.serie != undefined" required>
                            <option  v-for="sa in information.serie.saisons" :value="sa.id"> {{ sa.type }} {{ sa.numero}}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="numero">Numéro</label>
                        <input type="number" class="form-control" id="numero" min="0" v-model="information.numero" required>
                    </div>
                </div>
                <div class="form-row" v-if="information.serie != undefined && information.serie.type == 'Animes' ">
                    <div class="form-group col-md-4">
                        <label for="encodage">Encodage</label>
                        <select id="encodage" class="form-control" v-model="information.encodage">
                            <option value="0">En Attente</option>
                            <option value="50">En Cours</option>
                            <option value="100">Terminé</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="adapt">Adaptation</label>
                        <select id="adapt" class="form-control" v-model="information.adapt">
                            <option value="0">En Attente</option>
                            <option value="50">En Cours</option>
                            <option value="100">Terminé</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="edition">Edition</label>
                        <select id="edition" class="form-control" v-model="information.edition">
                            <option value="0">En Attente</option>
                            <option value="50">En Cours</option>
                            <option value="100">Terminé</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="qcheck">Qcheck</label>
                        <select id="qcheck" class="form-control" v-model="information.qcheck">
                            <option value="0">En Attente</option>
                            <option value="50">En Cours</option>
                            <option value="100">Terminé</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="traduction">Traduction</label>
                        <select id="traduction" class="form-control" v-model="information.traduction">
                            <option value="0">En Attente</option>
                            <option value="50">En Cours</option>
                            <option value="100">Terminé</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="time">Time</label>
                        <select id="time" class="form-control" v-model="information.time">
                            <option value="0">En Attente</option>
                            <option value="50">En Cours</option>
                            <option value="100">Terminé</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="check">Check</label>
                        <select id="check" class="form-control" v-model="information.check">
                            <option value="0">En Attente</option>
                            <option value="50">En Cours</option>
                            <option value="100">Terminé</option>
                        </select>
                    </div>
                </div>
                <div class="form-row" v-if="information.serie != undefined && (information.serie.type == 'Light-Novel' || information.serie.type == 'Scantrad')">
                    <div class="form-group col-md-4">
                        <div class="form-group">
                            <label for="clean">Clean / Redraw</label>
                            <input type="range" class="form-control-range" id="clean" v-model="information.time" min="0" max="100">
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <div class="form-group">
                            <label for="traductions">Traduction</label>
                            <input type="range" class="form-control-range" id="traductions" v-model="information.traduction" min="0" max="100">
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <div class="form-group">
                            <label for="acheck">Adapt / Check </label>
                            <input type="range" class="form-control-range" id="acheck" v-model="information.adapt" min="0" max="100">
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <div class="form-group">
                            <label for="mise">Mise en Forme</label>
                            <input type="range" class="form-control-range" id="mise" v-model="information.check" min="0" max="100">
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <div class="form-group">
                            <label for="qc">Qcheck</label>
                            <input type="range" class="form-control-range" id="qc" v-model="information.qcheck" min="0" max="100">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="publication" class="col-sm-2 col-form-label" >Visible</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="publication" v-model="information.publication">
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-outline-danger btn-block" @click="visible=false">Annuler</button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-outline-success btn-block" >Enregistrer</button>
                    </div>
                </div>

            </form>
        </div>
        <div class="container text-right" v-if="visible == false">
            <button type="button" class="btn btn-success btn-sm" @click="visible = true">Ajouter</button>
        </div>
        <div class="container mt-5" v-if="data != null && info == true && visible == false">
            <div class="row">
                <div class="col-md-3" v-for="da in data">
                    <div class="card mb-3" @click="disque(da)">
                        <img :src="da.serie.image" class="card-img-top cursor" alt="" >
                        <div class="card-body">
                            <h5 class="card-title text-truncate">
                                {{ da.serie.titre }}
                            </h5>
                            <div class="card-text" >
                                <div :id="'test'+da.id"></div>
                                <button type="button" class="btn btn-success btn-sm" @click="information = da; visible = true">Modifier</button>
                                <button type="button" class="btn btn-danger btn-sm"@click="getDestroy(da)">Supprimer</button>
                            </div>
                            <p class="card-text">
                                <small class="text-muted">
                                    <li class="fa fa-check text-success" v-if="da.publication == 1"></li>
                                    <li class="fa fa-times text-danger" v-if="da.publication == 0"></li>
                                    {{ da.type }} Saison {{da.saison.numero}} Numéro {{ da.numero }}
                                </small>
                            </p>
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
    </div>

</template>
<script>
    import ApexCharts from 'apexcharts'
    export default {
        data(){
            return {
                data:null,
                info:false,
                serie:null,
                information:{traduction:0,adapt:0,check:0,qcheck:0,time:0,encodage:0,edition:0, publication:0},
                visible : false,
            }


        },
        computed: {
            user(){
                return this.$store.getters.getProfile
            }
        },
        methods: {
            formulaire() {
                this.info = false
                const data = this.information
                if (!data.id){
                    axios.post('/api/administration/avancements', data)
                        .then(response =>{
                            if(response.data.success == true){
                                this.information={traduction:0,adapt:0,check:0,qcheck:0,time:0,encodage:0,edition:0, publication:0};
                                this.visible = false
                                this.getInfo()
                            }else{
                                this.info = true
                            }
                        })
                }else{
                    axios.put('/api/administration/avancements', data)
                        .then(response =>{
                            if(response.data.success == true){
                                this.information={traduction:0,adapt:0,check:0,qcheck:0,time:0,encodage:0,edition:0, publication:0};
                                this.visible = false
                                this.getInfo()
                            }else{
                                this.info = true
                            }
                        })
                }
            },
            getInfo(){
                axios.get('/api/administration/avancements')
                    .then(response => {
                        this.data = response.data.data
                        this.info = true
                        this.$parent.titre = "Gestion des avancements"
                    })

            },
            getSerie(){
                axios.get('/api/administration/series')
                    .then(response => {
                        this.serie = response.data.data
                    })

            },
            disque(a){
                console.log( a.cpt++)

                if(!a.visible){
                    a.cpt = 0;
                    a.visible = true;
                    var options1 = {
                        chart: {
                            height: 280,
                            type: "radialBar",
                        },
                        series: [a.encodage, a.adapt, a.edition, a.qcheck, a.traduction, a.time, a.check],
                        plotOptions: {
                            radialBar: {
                                dataLabels: {
                                    total: {
                                        show: true,
                                        label: 'TOTAL',
                                        formatter: function (w) {
                                            return Math.round(w.globals.seriesTotals.reduce((a, b) => {
                                                return a + b
                                            }, 0) / w.globals.series.length) + '%'
                                        }
                                    }
                                }
                            }
                        },
                        labels: ['encodage', 'Adaptation', 'Edition', 'Qcheck', 'Traduction', 'Time', 'Check']
                    };
                    if(document.querySelector("#test"+a.id)){
                        new ApexCharts(document.querySelector("#test"+a.id), options1).render();
                    }
                }
                if (a.cpt == 5){
                    a.visible = false
                }


            },
            getDestroy(info){
                let options = {
                    okText: 'Confirmer',
                    cancelText: 'Fermer',
                    type:'hard',
                    verification: 'Supprimer',
                    verificationHelp: 'Tapez [+:verification] avant de confirmer',
                    message: 'Etes-vous sur de vouloir supprimer l\'avancement',
                    clicksCount: 1,
                    backdropClose: true,
                };
                this.$dialog.confirm(options.message,options)
                    .then(() => {
                        axios.delete('/api/administration/avancements/'+info.id)
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
            this.$parent.titre = "Chargements ..."
            this.getInfo()
            this.getSerie()
        }
    }
</script>