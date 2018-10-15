<template>
    <div >
        <div class="col-md-12">
            <div class="row bg-img p-3">
                <div class="col-md-10 offset-md-1 p-1">
                    <b class="titre-detail">{{ serie.titre_original }}</b>
                </div>
                <div class="col col-lg-4 col-md-12 col-12 offset-lg-1 relative p-0">
                    <div class="triangle" v-if="serie.abo">
                        <p><i class="fas fa-bookmark"></i></p>
                    </div>
                    <img :src="serie.image" width="100%" class="img-detail">
                </div>
                <div class="col col-lg-6 col-12 col-md-12">
                    <div class="row bg-grey p-3 ml-2">
                        <div class="col-md-7">
                            <div class="p-1"><span class="info-detail">Titre alternatif </span>{{ serie.titre_alternatif }}</div>
                            <div class="p-1"><span class="info-detail">Studio(s) </span>{{ serie.studio }}</div>
                            <div class="p-1"><span class="info-detail">Auteur(s) </span>{{ serie.auteur }}</div>
                            <div class="p-1"><span class="info-detail">Genre(s) </span>
                                <span v-for="genre in serie.genres">
                                {{ genre.name}}
                                </span>
                            </div>
                            <div class="p-1" v-if="serie.episode != 0"><span class="info-detail">Episode(s) </span>{{ serie.episode }}</div>
                            <div class="p-1" v-if="serie.oav != 0"><span class="info-detail">Oav(s) </span>{{ serie.oav }}</div>
                            <div class="p-1" v-if="serie.films != 0"><span class="info-detail">Films(s) </span>{{ serie.films }}</div>
                            <div class="p-1" v-if="serie.bonus != 0"><span class="info-detail">Bonus(s) </span>{{ serie.bonus }}</div>
                            <div class="p-1" v-if="serie.scan != 0"><span class="info-detail">Scan(s) </span>{{ serie.scan }}</div>
                            <div class="p-1" v-if="serie.ln != 0"><span class="info-detail">Light-Novel(s) </span>{{ serie.ln }}</div>
                            <div class="p-1" v-if="serie.vn != 0"><span class="info-detail">Visual-Novel(s) </span>{{ serie.vn }}</div>

                        </div>
                        <div class="col-md-5">
                            <div class="p-1">
                                <span class="info-detail">Année </span>
                                {{ serie.annee }}
                            </div>
                            <div class="p-1">
                                <span class="info-detail">Etat </span>
                                <span class="text-primary" v-if="serie.etat == 0">En Cours</span>
                                <span class="text-success" v-if="serie.etat == 1">Terminé</span>
                                <span class="text-warning" v-if="serie.etat == 2">Abandonné</span>
                                <span class="text-danger" v-if="serie.etat == 3">Licencié</span>
                            </div>

                            <div class="row mt-4">
                                <div class="col text-center border-right">
                                    <h1><i class="fas fa-bookmark colorise"></i></h1>
                                    <small class="colorise">{{ serie.suivis }} suivis</small>
                                </div>
                                <div class="col text-center">

                                    <a class="nostyle colorise" v-if="serie.abo">
                                        <h1><i class="fas fa-bell-slash  colorise"></i></h1>
                                        <small>Supprimer</small>
                                    </a>

                                    <a class="nostyle colorise" v-else>
                                        <h1><i class="fas fa-bell colorise"></i></h1>
                                        <small>Suivre</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-news pt-2">
            <div class="container mt-3">
                <div class="seeMore" v-bind:style="{'max-height': style.height}">
                    <div class="categorie"><i class="icon fas fa-star"></i> <b>Synopsis</b></div>
                    <p class="mt-2" style="white-space: pre-line">{{serie.synopsis}}</p>
                    <div class="categorie"><i class="icon fas fa-star"></i> <b>Staff</b></div>
                    <p class="mt-2 text-center" style="white-space: pre-line">{{ serie.staff }}</p>
                </div>
                <div class="text-center p-1 cursor" id="seeMore" @click='toggle()' v-html="isText"></div>
            </div>
        </div>
        <div class="pt-2" v-if="serie.saisons != ''">
            <div class="container mt-3">
                <div class="categorie"><i class="icon fas fa-star"></i> <b>Téléchargement</b></div>
                <div class="card" v-for="saison in serie.saisons">
                    <div class="card-header cursor" data-toggle="collapse" :data-target="'#saison'+saison.id" v-if="saison.nosaison == 0">
                        <h5 class="mb-0 col-title" >
                            {{saison.type}} {{saison.numero}}: {{ saison.name }}
                        </h5>
                    </div>
                    <div :id="'saison'+saison.id" class="collapse" v-bind:class="[ saison.nosaison ? 'show' : '']">
                        <div class="card-body">
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
                infos: false,
                isText: 'Voir plus <i class="fas fa-arrow-alt-circle-down"></i>',
                isPlus: 'Voir plus <i class="fas fa-arrow-alt-circle-down"></i>',
                isMoin: 'Cacher <i class="fas fa-arrow-alt-circle-up"></i>',
                isOpen: false,
                style: {
                    height: '120px'
                },
            }
        },
        props: {

        },
        computed: {
            serie(){
                this.infos = this.$store.getters.getSerie;
                return this.infos
            },

        },
        watch: {
            infos() {
                this.$parent.titre = this.infos.titre
            }
        },

        methods:{
            toggle: function(){
                this.isOpen = !this.isOpen
                if (this.isOpen){
                    this.style.height = '3000px'
                    this.isText = this.isMoin
                }else{
                    this.style.height = '120px'
                    this.isText = this.isPlus
                }
            },
            async titre(){
                this.donne = await this.series().titre;
                if (this.donne != undefined){
                    console.log('oui')
                    console.log(this.donne)
                }else{
                    this.donne = this.serie.titre;
                    this.titre()
                    console.log('non')
                }
            },



        },
        beforeMount(){
            this.$appName = 'coucou';
            let type = this.$route.params.type;
            let slug = this.$route.params.slug;
            this.type = type;
            this.slug = slug;
            if(!type){
                this.$router.push('/');
            }
            if(type === 'Animes' || type === 'Scantrad' || type === 'Light-Novel' || type === 'Visual-Novel'){
                if (this.$store.getters.isAuthenticated) {
                    this.$store.dispatch('SerieInfoRequestLog', {type, slug});
                }
                else {
                    this.$store.dispatch('SerieInfoRequest', {type, slug});
                }
            }
            else{
                this.$router.push('/');
            }
        },
        mounted(){


        }
    }
</script>
