<template>
    <section :class="theme">
        <main>
            <navbar-component :theme="theme" :titre="titre" :sync="etat"></navbar-component>
            <router-view :user="user" :type="type"></router-view>
            <notifications group="foo" position="bottom right"></notifications>
        </main>
        <footer>
            <div class="bg-grey p-5 mt-5 footer">
                <div class="container footer-icon">
                    <div class="col-auto text-center">

                        <a href="https://discord.gg/hgXXvxf" rel="noreferrer" class="text-body" target="_blank">
                            <i class="fab fa-discord p-1"></i>
                        </a>
                        <a href="https://twitter.com/chuushin_kira" rel="noreferrer" target="_blank" class="text-body">
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
                    <small>© 2018 - {{annee}}  Chuushin no Fansub</small>
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
                theme:'light',
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
                        this.theme = this.user.data.theme
                    }
                }

            },

        },
        methods: {
            reload(){

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
