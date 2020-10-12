<template>
    <section :class="theme">
        <main>
            <navbar-component :titre="titre" :theme="theme" :sync="etat"></navbar-component>
            <div class="background_wrap"></div>
            <div class="banniere">
                <div class="quote_container">
                    <div class="img_quote">
                        <img src="/img/quote.png" alt="quote" width="70" height="56">
                        <h3>J'étais mort jusqu'à notre rencontre. </h3>
                    </div>
                    <h3 class="ligne">J'étais un cadavre impuissant, prétendant être vivant.”</h3>
                    <h4 class="author"> Lelouch Lamperouge // Code Geass </h4>
                </div>
            </div>
            <router-view></router-view>
        </main>
        <footer>
            <div class="background-caractere"></div>
            <div class="bg-grey p-5 mt-5 footer">
                <div class="container footer-icon">
                    <div class="col-auto text-center">
                        <a href="https://discord.gg/hgXXvxf" class="text-body " target="_blank">
                            <i class="fab fa-discord p-1"></i>
                        </a>
                        <a href="https://twitter.com/chuushin_kira" target="_blank" class="text-body">
                            <i class="fab fa-twitter p-1"></i>
                        </a>
                        <i class="fas fa-rss p-1"></i>
                        <i class="fas fa-envelope p-1"></i>
                    </div>
                </div>
                <div class="col-auto text-center p-1 footer-text">
                    <router-link class="colorise" :to="{name:'accueil'}">Accueil</router-link> |
                    <router-link class="colorise" :to="{name:'equipes'}">Equipes</router-link> |
                    <router-link class="colorise" :to="{name:'contact'}"> Nous contacter</router-link>
                    <br>
                    <small>© 2003 - {{annee}}  SeedTeam // Design by <b class="text-warning">Unforgiven</b> </small>
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
                titre:'Sa spécialité est de n\'avoir aucune spécialité',
                theme:'seedteam',
                description:'Site de la Chuushin no Fansub',
                image:'/img/logo.png',
                type:'all',
                annee:'',
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
                if(this.user != undefined){
                    this.etat = true
                    if(this.user.data != undefined){
                        this.etat = false;
                        //this.theme = this.user.data.theme
                    }
                }

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
            var ladate=new Date()
            this.annee =ladate.getFullYear();
        }

    }
</script>
