<template>
    <section :class="theme">
        <main>
            <navbar-component :titre="titre" ></navbar-component>
            <router-view :user="user" :type="type"></router-view>
            <notifications group="foo" position="top right">
                <template slot="body" slot-scope="props">
                    <div class="toast mt-2" role="alert" aria-live="assertive" aria-atomic="true" style="opacity: 1">
                        <div class="toast-header">
                            <strong class="mr-auto">{{props.item.title}}</strong>
                            <small class="text-muted">just now</small>
                            <button type="button" class="ml-2 mb-1 close" @click="props.close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body" v-html="props.item.text"></div>
                    </div>

                </template>
            </notifications>
        </main>
        <footer>
            <div class="bg-grey p-5 mt-5 footer">
                <div class="container footer-icon">
                    <div class="col-auto text-center">
                        <i class="fab fa-discord p-1"></i>
                        <i class="fab fa-twitter p-1"></i>
                        <i class="fas fa-rss p-1"></i>
                        <i class="fas fa-envelope p-1"></i>
                    </div>
                </div>
                <div class="col-auto text-center p-1 footer-text">
                    Accueil | Equipes | Nous contacter | Partenaires
                    <br>
                    <small>© 2018 Chuushin no Fansub</small>
                </div>
            </div>
        </footer>
    </section>

</template>

<script>
    export default {

        data(){
            return {
                etat : false,
                titre:'Accueil',
                theme:'light',
                description:'Site de la Chuushin no Fansub',
                image:'/img/logo.png',
                type:'all',
            }
        },
        metaInfo () {
            return {
                title: "Chuushin",
                titleTemplate: "%s | "+this.titre,
                meta: [
                    {vmid: 'description', name: 'description', content: this.description},
                    {
                        'property': 'og:title',
                        'content': 'Chuushin',
                        'template': '%s | '+this.titre,
                        'vmid': 'og:title'
                    },
                    {
                        'property': 'og:description',
                        'content': this.description,
                        'vmid': 'og:description'
                    },
                    {
                        'property': 'og:image',
                        'content': process.env.MIX_APP_URL+this.image,
                        'vmid': 'og:image'
                    },
                    {
                        'property': 'og:site_name',
                        'content': "Chuushin no Fansub",
                        'vmid': 'og:site_name'
                    },
                    {
                        'property': 'og:url',
                        'content': process.env.MIX_APP_URL+this.$route.fullPath,
                        'vmid': 'og:url'
                    },
                    {
                        'property': 'twitter:card',
                        'content': "summary",
                        'vmid': 'twitter:card'
                    },
                    {
                        'property': 'twitter:image:alt',
                        'content': this.description,
                        'vmid': 'twitter:image:alt'
                    },
                    {
                        'property': 'twitter:image',
                        'content': process.env.MIX_APP_URL+this.image,
                        'vmid': 'twitter:image'
                    },
                    {
                        'property': 'twitter:description',
                        'content': this.description,
                        'vmid': 'twitter:description'
                    },
                    {
                        'property': 'twitter:site',
                        'content': "@chuushin_kira",
                        'vmid': 'twitter:site'
                    },
                ]
            }
        },
        watch:{
            theme(){
            },
            user(){
                if(this.etat == false){
                    this.etat = true;
                    this.listenForActivity();
                    this.listenUser();
                    this.theme = this.user.data.theme
                }
            },

        },
        methods: {
            reload(){

            },
            listenForActivity() {
                //Echo.private('activity.' + this.user.data.equipe)
                //   .listen('ActivityLogged', (e) => {
                //       this.$notify({
                //           group: 'foo',
                //           title: "Nouvelle activité",
                //           text: e.data.description,
                //       });
                //       console.log(e.data.description);
                //       //this.feed.unshift(e.data);
                //   });
            },
            listenUser(){
                Echo.private('App.User.' + this.user.data.id)
                    .listen('userEvent', (e) => {
                       if(e.param == 'reload'){
                           this.$store.dispatch('userRequest');
                       }
                    })
            },

        },
        computed: {
            user(){
                if (this.$store.getters.isAuthenticated) {
                    return this.$store.getters.getProfile
                }
            }
        },
        mounted(){

        }

    }
</script>
