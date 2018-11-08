<template>
    <div>
        <div class="container">
            <div class="owl-carousel owl-one p-3">
                <div style="position: relative" class="img-thumbnail" data-tooltip="projets" data-serie="1">
                    <div class="item-overlay" ></div>
                    <img width="100%" src="https://image.chuushin-no-fansub.fr/projets/kokoro%20connect%20fiche%20projet%20720p_.png" alt="Card image cap">
                    <div class="slider__content">
                        <h4>
                            Titre
                        </h4>
                    </div>
                </div>
                <div style="position: relative" class="img-thumbnail">
                    <div class="item-overlay"></div>
                    <img width="100%" src="https://image.chuushin-no-fansub.fr/projets/kokoro%20connect%20fiche%20projet%20720p_.png" alt="Card image cap">
                    <div class="slider__content">
                        <h4>
                            Titre
                        </h4>
                    </div>
                </div>
                <div style="position: relative" class="img-thumbnail">
                    <div class="item-overlay"></div>
                    <img width="100%" src="https://image.chuushin-no-fansub.fr/projets/kokoro%20connect%20fiche%20projet%20720p_.png" alt="Card image cap">
                    <div class="slider__content">
                        <h4>
                            Titre
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-img p-3">
            <div class="container">
                <div class="titre">
                    <div class="row">
                        <div class="col col-md-8">
                            <i class="icon fas fa-bolt"></i> <b class="sortie">Dernières sorties</b>
                        </div>
                        <div class="col col-md-4 navSlide no-mobile">
                            <div class="customNavigation">
                                <a class="btn prev"><i class="fas fa-chevron-left"></i></a>
                                <a class="btn next"><i class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="owl-two owl-carousel owl-theme">
                    <div class="item">
                        <div class="card effect">
                            <img class="card-img-top zoom" data-tooltip="serie" data-serie="1" src="https://image.chuushin-no-fansub.fr/projets/kokoro%20connect%20fiche%20projet%20720p_.png" alt="Card image cap">
                        </div>
                    </div>
                    <div class="item">
                        <div class="card effect">
                            <img class="card-img-top zoom" data-tooltip="serie" data-serie="1" src="https://image.chuushin-no-fansub.fr/projets/kokoro%20connect%20fiche%20projet%20720p_.png" alt="Card image cap">
                        </div>
                    </div>
                    <div class="item">
                        <div class="card effect">
                            <img class="card-img-top zoom" data-tooltip="serie" data-serie="1" src="https://image.chuushin-no-fansub.fr/projets/kokoro%20connect%20fiche%20projet%20720p_.png" alt="Card image cap">
                        </div>
                    </div>
                    <div class="item">
                        <div class="card effect">
                            <img class="card-img-top zoom" data-tooltip="serie" data-serie="1" src="https://image.chuushin-no-fansub.fr/projets/kokoro%20connect%20fiche%20projet%20720p_.png" alt="Card image cap">
                        </div>
                    </div>
                    <div class="item">
                        <div class="card effect">
                            <img class="card-img-top zoom" data-tooltip="serie" data-serie="1" src="https://image.chuushin-no-fansub.fr/projets/kokoro%20connect%20fiche%20projet%20720p_.png" alt="Card image cap">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container news">
            <div class="titre categorie">
                <div class="row">
                    <div class="col col-md-12">
                        <i class="icon fas fa-star"></i> <b class="sortie">Nouveautés</b>
                    </div>
                </div>
            </div>
            <div class="row col col-xs-12 mt-3">
                <div class="card card-news mb-3" v-for="n in contenu">
                    <div class="row ">
                        <div class="col-md-3 img-news">
                            <img :src="n.image" class="">
                        </div>
                        <div class="col-md-9 ">
                            <div class="card-block px-1">
                                <h4 class="card-title pb-3 pt-3 categorie">
                                    <i class="icon fas fa-video" v-if="n.type == 'Animes'"></i>
                                    <i class="icon fas fa-book-open" v-if="n.type == 'Light-novel'"></i>
                                    <i class="icon fas fa-paint-brush" v-if="n.type == 'Scantrad'"></i>
                                    <i class="icon fas fa-gamepad" v-if="n.type == 'Visual-novel'"></i>
                                    <i class="icon fas fa-globe" v-if="n.type == 'all'"></i>
                                    <b>{{ n.titre }}</b>
                                </h4>
                                <p class="card-text">
                                    {{ n.contenu.slice(0, 300) }} ...
                                </p>
                            </div>
                            <div class="footer">
                                <div class="row">
                                    <div class="col-6">
                                        {{ n.publish_at | moment('Do MMMM YYYY') }}
                                    </div>
                                    <div class="col-6 text-right">
                                        Lire la suite
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <button type="button"
                        class="btn btn-secondary btn-lg btn-block"
                        @click="next()"
                        v-if="page != lastPage">
                    Voir Plus de nouveautés
                </button>
            </div>
        </div>
    </div>


</template>
<script>
    export default {
        data(){
                return {
                    data:{},
                    contenu:{},
                    page:1,
                    lastPage:false,
                    nextPage:1
                }


        },
        computed: {
            news(){
                return this.$store.getters.getNews;
            },
        },
        watch:{
            news(){
                    this.page=this.news.pagination.current_page,
                    this.lastPage=this.news.pagination.last_page,
                    this.nextPage=this.news.pagination.current_page +1
                    this.contenu = Object.assign(this.data, this.news.data)
            }
        },
        methods: {
            next(){
                this.paginate()
            },
            paginate(){
                const { nextPage, page } = this
                this.$store.dispatch('NewsRequest', {nextPage});
            },
        },
        mounted(){
            this.$parent.titre = "Accueil"
            this.paginate()

        }
    }
</script>