<template>
    <div>
        <div v-show="info">
            <transition name="custom-classes-transition"enter-active-class="animated fadeIn">
                <div v-if="serie.banniere" class="banner" :style="'background-image: url('+serie.banniere+');'">
                    <div data-v-b475b770="" class="shadow"></div>
                </div>
            </transition>
            <div class="bg-img pb-3">
                <div class="container">
                    <div class="row">
                        <transition name="custom-classes-transition"enter-active-class="animated fadeInLeft">
                            <div class="col-md-3 position-relative no-mobile" v-bind:class="{ 'cover-wrap': serie.banniere }" v-if="serie.image">
                                <img :src="serie.image" width="100%" class="img-detail mt-5">
                            </div>
                        </transition>
                        <div class="col-md-9 bg-grey p-3 mt-2">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="p-1"><span class="info-detail">Titre original </span>{{ serie.titre_alternatif }}</div>
                                    <div class="p-1"><span class="info-detail">Titre alternatif </span>{{ serie.titre_original }}</div>
                                    <div class="p-1" v-if="serie.type == 'Animes'"><span class="info-detail">Studio(s) </span>{{ serie.studio }}</div>
                                    <div class="p-1"><span class="info-detail">Date </span>
                                        {{ serie.annee | moment('MMMM YYYY') }}
                                    </div>
                                    <div class="p-1">
                                        <span class="info-detail">Etat </span>
                                        <span class="text-primary" v-if="serie.etat == 0">En Cours</span>
                                        <span class="text-success" v-if="serie.etat == 1">Terminé</span>
                                        <span class="text-warning" v-if="serie.etat == 2">Abandonné</span>
                                        <span class="text-danger" v-if="serie.etat == 3">Licencié</span>
                                    </div>
                                    <div class="p-1" v-if="serie.episode != 0">
                                        <span class="info-detail" v-if="serie.type == 'Animes'">Episodes</span>
                                        <span class="info-detail" v-if="serie.type == 'Scantrad' || serie.type == 'Light-Novel'">Chapitres</span>
                                        {{serie.episodes}} / {{ serie.episode }}
                                    </div>
                                    <div class="p-1" v-if="serie.type ==  'Scantrad' ||serie.type == 'Light-Novel'"><span class="info-detail">Tomes</span>{{ serie.scan }}</div>
                                </div>
                                <div class="col-md-5">
                                    <div class="row mt-4">
                                        <div class="col text-center border-right">
                                            <h1><i class="fas fa-bookmark colorise"></i></h1>
                                            <small class="colorise" v-if="serie.abonnement != 0">{{ serie.abonnement }} suivis</small>
                                            <small class="colorise" v-else="">Soyer le premier à suivre cette série</small>
                                        </div>
                                        <div class="col text-center">
                                            <form method="POST" v-on:submit.prevent="Abonnement">
                                                <button class="btn btn-link nostyle colorise p-0" type="submit" v-if="serie.abo">
                                                    <h1><i class="fas fa-bell-slash  colorise"></i></h1>
                                                    <small>Supprimer</small>
                                                </button>
                                                <button class="btn btn-link nostyle colorise p-0" type="submit"  v-else>
                                                    <h1><i class="fas fa-bell colorise"></i></h1>
                                                    <small>Suivre</small>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" v-if=" serie.genres">
                                    <div class="p-1" v-if="serie.genres.length != 0"><span class="info-detail">Genres </span>
                                        <span v-for="genre in serie.genres">
                                        {{ genre.name}}
                                    </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
            <div class="card-news pt-2">
                <div class="container mt-3">
                        <div class="categorie"><i class="icon fas fa-sticky-note"></i> <b>Synopsis</b></div>
                        <p class="mt-2" style="white-space: pre-line">{{serie.synopsis}}</p>
                    <template v-if="serie.staff != 'Pas de staff pour le moment'">
                        <div class="categorie"><i class="icon fas fa-users"></i> <b>Staff</b></div>
                        <p class="mt-2 text-center" style="white-space: pre-line">{{ serie.staff }}</p>
                    </template>
                </div>
            </div>
            <div class="pt-2" v-if="serie.saisons != '' || serie.episode != 0">
                <div class="container mt-3">
                    <div class="categorie"><i class="icon fas fa-file-download"></i> <b>Téléchargements</b></div>
                    <div class="card bg-transparent" v-for="saison in serie.saisons">
                        <div class="card-header cursor" data-toggle="collapse" :data-target="'#saison'+saison.id" v-if="saison.nosaison == 0">
                            <h5 class="mb-0 col-title" >
                                {{saison.type}} {{saison.numero}}: {{ saison.name }}
                            </h5>
                        </div>
                        <div :id="'saison'+saison.id" class="collapse " aria-expanded="true" v-bind:class="[ saison.nosaison ? 'show' : '']">
                            <div class=" bg-transparent" v-if="saison.episodes != ''">
                                <div class="media mt-3 border shadow-sm p-3 bg-white rounded" v-for="episode in saison.episodes">
                                    <img class="align-self-center mr-3"  :src="episode.image" alt=""  style="max-height: 82px" v-if="episode.image != 'noImage.jpg'">
                                    <img class="align-self-center mr-3"  :src="'https://via.placeholder.com/150?text='+episode.numero" alt=""  style="max-height: 82px" v-else="">
                                    <div class="media-body">
                                        <h5 class="mt-1">{{ episode.type }} {{ episode.numero }}: {{ episode.name }}</h5>
                                        <p>

                                            <div class="pull-right">
                                                <a v-show="episode.dvd != 'non' && serie.type == 'Animes'" :href="episode.dvd" @click="dl('dvd',episode)" class="btn" v-bind:class="[ episode.downloaddvd ? 'btn-success' : 'btn-secondary']" target="_blank">DVD</a>
                                                <a v-show="episode.hd != 'non' && serie.type == 'Animes'"  :href="episode.hd" @click="dl('hd', episode)" class="btn" v-bind:class="[ episode.downloadhd ? 'btn-success' : 'btn-secondary']" target="_blank">720P</a>
                                                <a v-show="episode.fhd != 'non' && serie.type == 'Animes'" :href="episode.fhd" @click="dl('fhd',episode)" class="btn" v-bind:class="[ episode.downloadfhd ? 'btn-success' : 'btn-secondary']" target="_blank">1080P</a>
                                                <router-link v-show="serie.type == 'Animes' && episode.etat >= 4" :to="{name:'streaming', params:{saison: saison.numero, episode:episode.numero}}" class="btn" v-bind:class="[ episode.stream ? 'btn-success' : 'btn-secondary']">Streaming</router-link>

                                                <a v-show="episode.hd != 'non' && serie.type == 'Scantrad'"  :href="episode.hd" @click="dl('hd', episode)" class="btn" v-bind:class="[ episode.downloadhd ? 'btn-success' : 'btn-secondary']" target="_blank">Télécharger </a>
                                                <router-link v-show="serie.type == 'Scantrad' && episode.etat == 5" :to="{name:'lecture', params:{saison: saison.numero, episode:episode.numero}}" class="btn" v-bind:class="[ episode.stream ? 'btn-success' : 'btn-secondary']">Lecture en ligne</router-link>

                                                <a v-show="episode.hd != 'non' && serie.type == 'Light-Novel'"  :href="'/storage/'+episode.hd" @click="dl('hd', episode)" class="btn" v-bind:class="[ episode.downloadhd ? 'btn-success' : 'btn-secondary']" target="_blank" download="">Télécharger </a>
                                                <router-link v-show="serie.type == 'Light-Novel'" :to="{name:'lightNovel', params:{saison: saison.numero, episode:episode.numero}}" class="btn" v-bind:class="[ episode.stream ? 'btn-success' : 'btn-secondary']">Lecture en ligne</router-link>
                                            </div>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center" v-else-if="saison.episode != '' && saison.nosaison != true">
                                Pas de téléchargements disponible pour le moment
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container" v-show="info == false">
            <div class="row mt-2">
                <div class="col-md-12 text-center">
                    <div class="spinner-border mt-5" style="width: 6rem; height: 6rem;" role="status">
                        <span class="sr-only">Chargement ...</span>
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
                serie:{},
                'abo':'',
                'serie_id':'',
                'action':'Abonnement',
                info: false,
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


        },
        watch: {
            infos() {
                this.$parent.titre = this.infos.titre
            }
        },

        methods:{
            getInfo(type, slug){
                axios.get('/api/projets/'+type+'/'+slug)
                    .then(response => {
                        this.info = true;
                        this.serie = response.data.data
                        this.$parent.titre = response.data.data.titre
                    })
            },
            dl:function(qualiter, episode){
                var episode_id = episode.id;
                var serie_id = this.serie.id;
                axios.post('/api/telechargements', {serie_id, qualiter, episode_id})
                    .then(response => {
                        this.getInfo(this.$route.params.type, this.$route.params.slug)
                    })

            },
            Abonnement: function () {
                var serie_id = this.serie.id;
                axios.post('/api/compte/abonnement', {serie_id})
                    .then(response => {
                        if(response.data.success == true){
                            if(response.data.data == true){
                                this.serie.abo = true
                                this.serie.abonnement++
                            }
                            else{
                                this.serie.abo = false
                                this.serie.abonnement--
                            }
                        }
                    })
                    .catch(error => {
                        this.$router.push('/login');
                    })
            }

        },
        beforeCreate(){
            this.$parent.titre = "Chargement ..."
        },
        created(){
            let type = this.$route.params.type;
            let slug = this.$route.params.slug;
            this.type = type;
            this.slug = slug;
            if(!type){
                this.$router.push('/');
            }
            if(type === 'Animes' || type === 'Scantrad' || type === 'Light-Novel' || type === 'Visual-Novel'){
                this.getInfo(type, slug);
            }
            else{
                this.$router.push('/');
            }
        },
        mounted(){
        },
    }
</script>