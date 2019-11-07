<template>
    <div>
        <div class="container mt-3">
            <div class="row mt-4 border bg-white" v-if="series == ''">
                <div class="col-md-12 text-center p-4">
                    Pas de série pour le moment
                </div>
            </div>
            <div class="row" >
                <div class="col-md-2 mb-4" v-for="serie in series" v-show="type == 'all' || type == serie.type" v-bind:key="serie.id">
                    <router-link :to="{ name: 'serieDetail', params: { type: encodeURI(serie.type) , slug: serie.slug} }" class="card-link">
                        <div class="card bg-white">
                            <div class="relative">
                                <img class="card-img-top" :src="serie.image" :alt="serie.titre" style="max-height: 220px">
                                <div class="triangle" v-if="serie.abo">
                                    <p><i class="fas fa-bookmark"></i></p>
                                </div>
                                <div class="type-serie">
                                    <div class="draggable">
                                        <i class="fas fa-video" v-if="serie.type == 'Animes'"></i>
                                        <i class="fas fa-paint-brush" v-if="serie.type == 'Scantrad'"></i>
                                        <i class="fas fa-book-open" v-if="serie.type == 'Light-Novel'"></i>
                                        <i class="fas fa-gamepad" v-if="serie.type == 'Visual-Novel'"></i>
                                    </div>
                                </div>
                                <div class="etat-serie">
                                    <span class="badge badge-primary" v-if="serie.etat == 0">En Cours</span>
                                    <span class="badge badge-success" v-if="serie.etat == 1">Terminé</span>
                                    <span class="badge badge-warning" v-if="serie.etat == 2">Abandonné</span>
                                    <span class="badge badge-danger" v-if="serie.etat == 3">Licencié</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-truncate text-center">{{serie.titre }}</p>
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>

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
