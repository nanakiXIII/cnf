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
                    <div class="row">
                        <div class="col-md-3 img-news">
                            <img :data-src="n.image" class="lazyload" alt="Chargement...">
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
                                <p class="card-text p-2" v-html="n.contenu.replace(/<[^>]*>/g, '').slice(0, 300)+' ...'">
                                </p>
                            </div>
                            <div class="footer">
                                <div class="row">
                                    <div class="col-6">
                                        <i class="fa fa-comments"></i> {{ n.comments.length}}
                                        | <i class="fa fa-calendar"></i> {{ n.publish_at.date | moment('Do MMMM YYYY') }}
                                    </div>
                                    <div class="col-6 text-right">
                                        <router-link :to="{name:'news', params:{slug:n.slug}}" :title="n.titre" class="colorise">
                                            Lire la suite
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </transition-group>
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
        watch:{
            news(){
                //this.page=this.news.pagination.current_page
                //this.lastPage=this.news.pagination.last_page
                //this.nextPage=this.news.pagination.current_page +1
                //this.from = this.news.pagination.from +10
                //this.contenu = Object.assign(this.data, this.news.data)
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
                        //this.news.data.push(response.data.data)
                        //this.news.data = this.news.data.push(response.data.data[0])
                        //this.data.push(response.data.data)
                        //this.data.push(response.data.data)
                        //this.news.data.push(response.data.data)
                    })
            },
            getInfo(){
                axios.get('/api/news')
                    .then(response => {
                        this.loading = false;
                        this.news = response.data
                        this.data = response.data.data
                    })
            },
            scroll () {
                let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight
                if (bottomOfWindow) {
                    if(this.loading == false && this.news.meta.current_page != this.news.meta.last_page){
                        this.next()
                    }

                }
            }
        },
        mounted(){
            window.addEventListener('scroll', this.scroll)
            this.$parent.titre = "Accueil"
            this.getInfo()
        },
        destroyed(){
            window.removeEventListener('scroll', this.scroll)
        }
    }
</script>