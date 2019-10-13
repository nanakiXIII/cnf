<template>
    <div>
        <div class="bg-img p-3 no-mobile" v-if="data != null">
            <div class="container">
                <div class="titre">
                    <div class="row">
                        <div class="col col-md-8">
                            <i class="icon fas fa-bolt"></i> <b class="sortie">Prochaines sorties</b>
                        </div>
                    </div>
                </div>
                <carousel :nav="false"
                          :dots="true"
                          :loop="false"
                          :autoplay="true"
                          :items="1"
                          :autoplayTimeout="5000"
                          :autoplayHoverPause="true">
                        <div class="item relative" v-for="d in data" :style="'background-image: url('+d.serie.banniere+'); background-size: cover; height:300px;'">
                            <div class="infoProjet fond">
                                <img :src="d.serie.image" :alt="d.serie.titre"  class="img">
                                <div class="text">
                                    <h5 class="text-center">
                                        {{ d.serie.titre }} {{ d.saison.type }} {{ d.saison.numero}} :
                                        <template v-if="d.type == 'Animes'"> Episode </template>
                                        <template v-if="d.type == 'Scantrad'"> Chapitre </template>
                                        <template v-if="d.type == 'Light-Novel'"> Chapitre </template>
                                        <template v-if="d.type == 'Visual-novel'"> Jeux </template>
                                        {{ d.numero }}
                                    </h5>
                                    <div class="row mt-4" v-if="d.type == 'Scantrad' || d.type == 'Light-Novel'">
                                        <div class="col-md-6">
                                            Clean / Redraw
                                            <div class="progress mb-2" style="height: 5px">
                                                <div class="progress-bar bg-colorise" role="progressbar" :style="'width:'+d.time+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            Traduction
                                            <div class="progress mb-2" style="height: 5px">
                                                <div class="progress-bar bg-colorise" role="progressbar" :style="'width:'+d.traduction+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            Adaptation / Check
                                            <div class="progress mb-2" style="height: 5px">
                                                <div class="progress-bar bg-colorise" role="progressbar" :style="'width:'+d.adapt+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            Mise en Forme
                                            <div class="progress mb-2" style="height: 5px">
                                                <div class="progress-bar bg-colorise" role="progressbar" :style="'width:'+d.check+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            Qcheck
                                            <div class="progress mb-2" style="height: 5px">
                                                <div class="progress-bar bg-colorise" role="progressbar" :style="'width:'+d.qcheck+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2" v-if="d.type == 'Animes'">
                                        <div class="col-md-6 mb-2">
                                            <li class="fa fa-check text-success" v-if="d.encodage == 100"></li>
                                            <li class="far fa-hourglass text-warning" v-if="d.encodage == 50"></li>
                                            <li class="fa fa-times text-danger" v-if="d.encodage == 0"></li>
                                            Encodage
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <li class="fa fa-check text-success" v-if="d.traduction == 100"></li>
                                            <li class="far fa-hourglass text-warning" v-if="d.traduction == 50"></li>
                                            <li class="fa fa-times text-danger" v-if="d.traduction == 0"></li>
                                            Traduction
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <li class="fa fa-check text-success" v-if="d.adapt == 100"></li>
                                            <li class="far fa-hourglass text-warning" v-if="d.adapt == 50"></li>
                                            <li class="fa fa-times text-danger" v-if="d.adapt == 0"></li>
                                            Adaptation Japonaise
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <li class="fa fa-check text-success" v-if="d.time == 100"></li>
                                            <li class="far fa-hourglass text-warning" v-if="d.time == 50"></li>
                                            <li class="fa fa-times text-danger" v-if="d.time == 0"></li>
                                            Time / Karaoké Design
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <li class="fa fa-check text-success" v-if="d.edition == 100"></li>
                                            <li class="far fa-hourglass text-warning" v-if="d.edition == 50"></li>
                                            <li class="fa fa-times text-danger" v-if="d.edition == 0"></li>
                                            Edition
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <li class="fa fa-check text-success" v-if="d.check == 100"></li>
                                            <li class="far fa-hourglass text-warning" v-if="d.check == 50"></li>
                                            <li class="fa fa-times text-danger" v-if="d.check == 0"></li>
                                            Check
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <li class="fa fa-check text-success" v-if="d.qcheck == 100"></li>
                                            <li class="far fa-hourglass text-warning" v-if="d.qcheck == 50"></li>
                                            <li class="fa fa-times text-danger" v-if="d.qcheck == 0"></li>
                                            Quality Check
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4 text-center">
                                                    <li class="fa fa-check text-success"></li> Terminé
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <li class="far fa-hourglass text-warning"></li> En Cours
                                                </div>
                                                <div class="col-md-4 text-center">
                                                    <li class="fa fa-times text-danger"></li> En attente
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                </carousel>

            </div>
        </div>
    </div>


</template>
<script>
    import carousel from 'vue-owl-carousel'
    export default {
        components: { carousel },
        data(){
            return {
                data:null
            }


        },
        watch:{

        },
        methods: {
            getInfo(){
                axios.get('/api/avancements')
                    .then(response => {
                        this.data = response.data.data
                    })

            },


        },
        mounted(){
            this.getInfo();
        },
        destroyed(){

        }
    }
</script>