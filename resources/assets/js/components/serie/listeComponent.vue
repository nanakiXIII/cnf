<template>
    <div>
        <div class="container mt-3">
            <div class="row mt-4 border bg-white" v-if="series == ''">
                <div class="col-md-12 text-center p-4">
                    Pas de série pour le moment
                </div>
            </div>
            <transition-group name="Projets" tag="div" enter-active-class="animated zoomIn" leave-active-class="animated zoomOut">
                <div class="row mt-4 border bg-white relative" v-for="serie in series" v-show="type == 'all' || type == serie.type" v-bind:key="serie.id">
                    <template v-if="serie">
                        <div class="triangle" v-if="serie.abo">
                            <p><i class="fas fa-bookmark"></i></p>
                        </div>
                        <div class="col-md-3 img-news row">
                            <img :src="serie.image">
                        </div>
                        <div class="col-md-9">
                            <div class="card-block px-1">
                                <router-link :to="{ name: 'serieDetail', params: { type: encodeURI(serie.type) , slug: serie.slug} }" class="title">
                                    <h4 class="card-title pb-1 pt-3 center-mobile"><b>{{ serie.titre }}</b></h4>
                                </router-link>
                                <p class="card-text bg-grey col-md-12 p-3 mb-3">
                                    <span class='info-tooltip'>Type</span>{{ serie.type }} <br>
                                    <span class='info-tooltip' v-if="serie.type == 'Animes'">Studio</span> <template v-if="serie.type == 'Animes'">{{ serie.studio }}<br></template>
                                    <span class='info-tooltip'>Année</span> {{ serie.annee | moment('MMMM YYYY') }} <br>
                                    <span class='info-tooltip'>Etat</span>
                                    <span class="text-primary" v-if="serie.etat == 0">En Cours</span>
                                    <span class="text-success" v-if="serie.etat == 1">Terminé</span>
                                    <span class="text-warning" v-if="serie.etat == 2">Abandonné</span>
                                    <span class="text-danger" v-if="serie.etat == 3">Licencié</span>
                                    <br>
                                    <span class='info-tooltip'>Genres</span>
                                    <span v-for="genre in serie.genres">
                                {{ genre.name}}
                            </span>
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </transition-group>

            <transition name="custom-classes-transition"enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" >
                <div class="container" v-show="info == false">
                    <div class="row mt-2">
                        <div class="col-md-12 text-center">
                            <div class="spinner-border mt-5" style="width: 6rem; height: 6rem;" role="status">
                                <span class="sr-only">Chargement ...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                series: {},
                info:false
            };
        },
        watch:{
            user() {
                if (this.user == undefined) {
                    this.getInfo()
                }
            }
        },
        props: {
            type : String,
        },
        computed:{
            user(){
                if (this.$store.getters.isAuthenticated) {
                    return this.$store.getters.getProfile
                }
            }
        },
        methods:{
            getInfo(){
                axios.get('/api/projets')
                    .then(response => {
                        this.info = true
                        this.series = response.data.data
                    })
            },
        },
        beforeCreate(){
            this.$parent.titre = "Chargement ..."
        },
        created(){
            this.getInfo()
        },
        mounted(){
            this.$parent.titre = "Nos projets"
        },
        destroyed() {

        }
    }
</script>
