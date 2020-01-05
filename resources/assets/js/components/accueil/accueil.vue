<template>
    <div>
        <projet></projet>
        <div class="container news">
            <div class="titre categorie">
                <div class="row">
                    <div class="col col-md-12">
                        <i class="icon fas fa-star"></i> <b class="sortie">Nouveautés</b>
                    </div>
                </div>
            </div>
            <div class="row col col-xs-12 mt-3">
                <transition-group name="news" tag="div" class="col-md-12" enter-active-class="animated zoomIn" leave-active-class="animated zoomOut">
                    <div class="card card-news mb-3 shadow" style="width: 100%!important; height: auto!important;" v-for="n in data" v-bind:key="n.id" v-if="n.contenu">
                    <div class="row m-0">
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
                                <p class="card-text p-2" v-html="n.contenu.replace(/<[^>]*>/g, '').slice(0, 300)+' ...'">
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 img-news img-rounded no-mobile">
                            <img :data-src="n.image" class="lazyload" alt="Chargement...">
                        </div>
                        <div class="col-md-12 footer">
                            <div class="row">
                                <div class="col-6 text-capitalize">
                                    <i class="fa fa-comments"></i> {{ n.comments.length}}
                                    <template v-if="n.comments.length >= 2">Commentaires</template>
                                    <template v-else="">Commentaire</template>
                                    | <i class="fa fa-calendar"></i> {{ n.publish_at.date | moment('Do MMMM YYYY') }}
                                </div>
                                <div class="col-6 text-right">
                                    <router-link :to="{name:'news', params:{slug:n.slug}}" :title="n.titre" class="colorise">
                                        <button type="button" class="btn btn-sm btn-outline-colorise">Lire la suite</button>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </transition-group>
                <template v-if="news.links">
                    <button type="button" @click="next()" v-if="news.links.next && news.links.next != null" class="btn btn-outline-colorise btn-lg btn-block">
                        Voir plus de news
                    </button>
                </template>

                <div class="container" v-show="loading">
                    <div class="row mt-2">
                        <div class="col-md-12 text-center">
                            <div class="spinner-border mt-5" style="width: 6rem; height: 6rem;" role="status">
                                <span class="sr-only">Chargement ...</span>
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
                    news:[],
                    data:{},
                    loading:true,
                }
        },
        methods: {
            next(){
                this.loading = true;
                axios.get(this.news.links.next)
                    .then(response => {
                        this.news = response.data
                        var data = response.data.data;
                        data.forEach(news => {
                            this.data.push(news)
                        });
                        this.loading = false;
                    })
            },
            getInfo(){
                axios.get('/api/news')
                    .then(response => {
                        this.loading = false;
                        this.news = response.data
                        this.data = response.data.data
                    })
            }
        },
        mounted(){
            this.$parent.titre = "Accueil"
            this.getInfo()
        }
    }
</script>
<style>
    .img-rounded{
        border-radius: 50% 0% 50% 50%;
    }
</style>