<template>
    <div>
        <div class="container news">
            <div class="row"v-if="news">
                <div class="card card-news p-3 shadow" style="width: 100%">
                    <div class="card-block px-1">
                        <p class="card-text" v-html="news.contenu"></p>
                        <div class="row">
                            <div class="col-md-12 col-xs-12" v-if="news.staff != 'Pas de staff pour le moment' && news.staff">
                                <h3 class="p-2 border-bottom">Staff</h3>
                                <p class="card-text text-center " style="white-space: pre-line">
                                    {{ news.staff}}
                                </p>
                            </div>
                            <div class="col-md-12 col-xs-12 mt-2" v-if="news.fichiers.length >=1">
                                <h3 class="p-2 border-bottom">Téléchargements</h3>
                                <p class="card-text">
                                    <div class="media mt-3 border shadow-sm p-3 bg-white rounded" v-for="episode in news.fichiers">
                                        <img class="align-self-center mr-3 no-mobile"  :src="episode.image" alt=""  style="max-height: 82px">
                                        <div class="media-body">
                                            <h5 class="mt-1">{{ episode.type }} {{ episode.numero }}: {{ episode.name }}</h5>
                                            <p>
                                            <div class="pull-right">
                                                <a v-show="episode.dvd != 'non' && episode.serie.type == 'Animes'" :href="episode.dvd" @click="dl('dvd',episode)" class="btn"  v-bind:class="[ episode.downloaddvd ? 'btn-success' : 'btn-secondary']" target="_blank">DVD</a>
                                                <a v-show="episode.hd != 'non' && episode.serie.type == 'Animes'"  :href="episode.hd" @click="dl('hd', episode)" class="btn" v-bind:class="[ episode.downloadhd ? 'btn-success' : 'btn-secondary']" target="_blank">720P</a>
                                                <a v-show="episode.fhd != 'non' && episode.serie.type == 'Animes'" :href="episode.fhd" @click="dl('fhd',episode)" class="btn" v-bind:class="[ episode.downloadfhd ? 'btn-success' : 'btn-secondary']" target="_blank">1080P</a>
                                                <router-link v-show="episode.serie.type == 'Animes'" :to="{name:'streaming', params:{type: episode.serie.type, slug: episode.serie.slug, saison: episode.saison.numero, episode:episode.numero}}" class="btn" v-bind:class="[ episode.stream ? 'btn-success' : 'btn-secondary']">Streaming</router-link>

                                                <a v-show="episode.hd != 'non' && episode.serie.type == 'Scantrad'"  :href="episode.hd" @click="dl('hd', episode, episode.serie_id)" class="btn" v-bind:class="[ episode.downloadhd ? 'btn-success' : 'btn-secondary']" target="_blank">Télécharger </a>
                                                <router-link v-show="episode.serie.type == 'Scantrad' && episode.etat == 5" :to="{name:'lecture', params:{type: episode.serie.type, slug: episode.serie.slug, saison: episode.saison.numero, episode:episode.numero}}" class="btn" v-bind:class="[ episode.stream ? 'btn-success' : 'btn-secondary']">Lecture en ligne</router-link>
                                            </div>
                                            </p>
                                        </div>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="footer mt-4">
                        <div class="row">
                            <div class="col-6">
                                {{ news.publish_at.date | moment('Do MMMM YYYY') }}
                            </div>
                            <div class="col-6 text-right">
                                {{ news.user.name }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card col-md-12 mt-3">
                    <div class="card-body row">
                        <h4 v-if="news.comments.length > 1">{{news.comments.length}} Commentaires</h4>
                        <h4 v-else="">{{news.comments.length}} Commentaire</h4>
                        <form v-on:submit.prevent="postComments()" class="col-md-12" v-if="save == false && user">
                            <div class="alert alert-danger" role="alert" v-if="erreur != null">
                                <ul class="list-group">
                                    <li v-for="(e, key) in erreur"> {{e[0]}}</li>
                                </ul>
                            </div>
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" v-model="commentaire"></textarea>
                            <div class="row">
                                <div class="col-md-6">
                                    <vue-recaptcha
                                            :sitekey="key"
                                            :loadRecaptchaScript="true"
                                            ref="recaptcha"
                                            @verify="onVerify"
                                            @expired="onExpired"
                                    >
                                    </vue-recaptcha>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="mt-2 btn btn-outline-colorise btn-sm btn-block">Envoyer</button>
                                </div>
                            </div>


                        </form>
                        <div class="col-md-12 text-center" v-if="save == true">
                            <div class="spinner-border mt-5" style="width: 6rem; height: 6rem;" role="status">
                                <span class="sr-only">Chargement ...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card col-md-12 mt-3 shadow" v-for="c in news.comments">
                    <div class="card-body row">
                        <div class="col-md-2">
                            <img :src="c.user.avatar" :alt="c.user.name" style="max-width: 100%">
                        </div>
                        <div class="col-md-10">
                            <div class="mb-2">
                                <i class="fa fa-users colorise" v-if="c.user.equipe == 1"></i>
                                <b>{{c.user.name}}</b>
                                <small>
                                    {{ c.created_at.date | moment("from", "now")}}
                                </small>
                            </div>
                            {{ c.comment }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="container" v-show="!news">
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


</template>
<style> p{ margin-bottom: 5px!important; }</style>
<script>
    import VueRecaptcha from 'vue-recaptcha';
    export default {
        components: { VueRecaptcha },
        data(){
            return {
                key:'6LdQM1YUAAAAABFGEJQYd9wju4lylDFW30GWKGWK',
                captcha : '',
                slug: this.$route.params.slug,
                news:false,
                commentaire:'',
                erreur:null,
                save: false,
            }


        },
        computed: {
            user(){
                if (this.$store.getters.isAuthenticated) {
                    return this.$store.getters.getProfile
                }
            }
        },
        watch:{
            '$route.params.slug'(){
                this.slug = this.$route.params.slug;
                this.chargeNews();
            }
        },
        methods: {
            onVerify: function (response) {
                this.captcha = true
            },
            onExpired: function () {
                this.captcha = ''
            },
            getInfo(){
                axios.get('/api/news/'+this.slug)
                    .then(response => {
                        this.news = response.data.data
                        this.$parent.titre = response.data.data.titre
                    })
            },
            dl:function(qualiter, episode){
                var episode_id = episode.id;
                var serie_id = episode.serie.id;
                console.log(episode.serie_id)
                axios.post('/api/telechargements', {serie_id, qualiter, episode_id})
                    .then(response => {

                    })

            },
            postComments(){
                this.save = true
                this.erreur = null;
                const id = this.news.id;
                const commentaire = this.commentaire;
                const captcha = this.captcha
                axios.post('/api/commentaires', {id, commentaire, captcha})
                    .then(response =>{
                        this.getInfo()
                        this.save = false
                        this.commentaire = ""
                    })
                    .catch(errors =>{
                        this.erreur = errors.response.data.errors
                        this.save = false
                    })

            }

        },
        mounted(){
            this.$parent.titre = 'chargements ...'
           this.getInfo();
        }
    }
</script>
<style>
    .ql-align-center{
        text-align: center;
    }
    .card-text img{
        max-width: 100%!important;
    }
</style>