<template>
    <section :class="theme">
        <main>
            <navbar-component :titre="titre"></navbar-component>
            <router-view></router-view>
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
                titre:'Accueil',
                theme:'light',
                description:'Site de la Chuushin no Fansub',
                image:'/img/logo.png'
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
                this.theme = this.user.theme
            },

        },
        methods: {

        },
        computed: {
            user(){
                if (this.$store.getters.isAuthenticated) {
                   return this.$store.getters.getProfile
                }

            }
        },
        mounted(){
            if (this.$store.getters.isAuthenticated) {
                this.theme = this.$store.getters.getProfile.theme;
            }

        }

    }
</script>
