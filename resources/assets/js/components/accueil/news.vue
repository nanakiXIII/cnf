<template>
    <div>
        <div class="container news">
            <div class="row">
                <div class="col-md-8" v-if="news.news">
                    <h2 class="mt-lg-4 mb-lg-4 text-center">{{ news.news.titre }}</h2>
                    <div class="row">
                        <div class="card card-news p-3">
                            <div class="card-block px-1">
                                <p class="card-text">
                                    {{ news.news.contenu}}
                                </p>
                                <img :src="news.news.image" alt="" width="100%" class="mb-3">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <h3 class="p-2 border-bottom text-center">Staff</h3>
                                        <p class="card-text text-center">
                                            {{ news.news.staff}}
                                        </p>
                                    </div>
                                    <div class="col-md-6 col-xs-12">
                                        <h3 class="p-2 border-bottom text-center">Téléchargements</h3>
                                        <p class="card-text">
                                            {{ news.news.staff}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="footer mt-4">
                                <div class="row">
                                    <div class="col-6">
                                        {{ news.news.publish_at | moment('Do MMMM YYYY') }}
                                    </div>
                                    <div class="col-6 text-right">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h4 class="text-center p-4">Nouveautés Similaires</h4>
                    <div class="col-md-12 card card-news"  v-if="news.simulare">
                        <template v-for="s in news.simulare">
                            <router-link :to="{name:'news', params:{slug:s.slug}}" class="no-decoration item mb-2 border mt-2" >
                                <div class="card effect">
                                    <img class="card-img-top zoom" :src="s.image" alt="Chargement ...">
                                </div>
                                <div class="text-center p-2">
                                    {{ s.titre }}
                                </div>
                                <small class="p-2">{{ s.publish_at | moment('Do MMMM YYYY') }}</small>

                            </router-link>
                        </template>
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
               slug: this.$route.params.slug
            }


        },
        computed: {
            news(){
                return this.$store.getters.getNews;

            },
        },
        watch:{
            news(){
                this.$parent.titre = this.news.news.titre
            },
            '$route.params.slug'(){
                this.slug = this.$route.params.slug;
                this.chargeNews();
            }
        },
        methods: {
            chargeNews(){
                const { slug } = this
                this.$store.dispatch('NewsRequest', {slug});
            },

        },
        mounted(){
            this.chargeNews();
        }
    }
</script>