<template>
    <div v-if="info == true">
        <div class="bg-img p-3">
            <div class="container p-4">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center">
                            {{projet.titre}} {{playlist[currentItem].episode.type}} {{playlist[currentItem].episode.numero}}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-3" v-if="currentItem != null">
            <div class="row mb-3">
                <div class="col-md-4">
                    <button type="button"
                            class="btn btn-outline-secondary btn-lg btn-block"
                            @click="choix('precedent')"
                            v-if="currentItem != 0">
                        Précédent
                    </button>
                </div>
                <div class="col-md-4">
                    <router-link :to="{ name: 'serieDetail', params: { type: encodeURI(projet.type) , slug: projet.slug} }" type="button" class="btn btn-outline-secondary btn-lg btn-block">Fiche</router-link>
                </div>
                <div class="col-md-4">
                    <button type="button"
                            class="btn btn-outline-secondary btn-lg btn-block"
                            @click="choix('suivant')"
                            v-if="playlist.length != currentItem +1">
                        Suivant
                    </button>
                </div>
            </div>
            <pdf
                    v-for="i in numPages"
                    :key="i"
                    :src="src"
                    :page="i"
                    style="display:block; width: 100%"
                    class="mb-3"
            ></pdf>
            <div class="row mt-3">
                <div class="col-md-4">
                    <button type="button"
                            class="btn btn-outline-secondary btn-lg btn-block"
                            @click="choix('precedent')"
                            v-if="currentItem != 0">
                        Précédent
                    </button>
                </div>
                <div class="col-md-4">
                    <router-link :to="{ name: 'serieDetail', params: { type: encodeURI(projet.type) , slug: projet.slug} }" type="button" class="btn btn-outline-secondary btn-lg btn-block">Fiche</router-link>
                </div>
                <div class="col-md-4">
                    <button type="button"
                            class="btn btn-outline-secondary btn-lg btn-block"
                            @click="choix('suivant')"
                            v-if="playlist.length != currentItem +1">
                        Suivant
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import pdf from 'vue-pdf'
    export default {
        components: {
            pdf
        },
        data() {
            return {
                src: null,
                numPages: undefined,
                currentPage: 0,
                pageCount: 0,
                info:'',
                projet:{},
                saisons: {},
                episodes:{},
                player: null,
                playlist:[],
                currentItem:null,
                ready:false,

            }
        },
        computed: {


        },
        watch: {
            projet(){
                this.saisons = this.projet.saisons.find(element => {
                    return element.numero == this.$route.params.saison
                })
            },
            saisons(){
                this.episodes = this.saisons.episodes.find(element => {
                    return element.numero == this.$route.params.episode
                })
            },
            episodes(){
                this.$parent.titre = "Lecture de " + this.projet.titre + " " + this.episodes.type + " " + this.episodes.numero
                var video = 0
                this.saisons.episodes.forEach(episode =>{
                    if(episode.id == this.episodes.id){
                        this.info = true;
                        this.currentItem = video

                    }
                    this.playlist.push({
                        poster: episode.image,
                        id:episode.id,
                        episode: episode
                    })
                    video++
                });
            },
            currentItem(){
                this.dl('lu', this.playlist[this.currentItem].episode)
                this.src = pdf.createLoadingTask('/storage/'+this.playlist[this.currentItem].episode.hd);
                this.filePdf()
            }
        },

        methods:{
            getInfo(type, slug){
                axios.get('/api/projets/'+type+'/'+slug)
                    .then(response => {
                        this.projet = response.data.data
                    })
            },
            dl:function(qualiter, episode){
                var episode_id = episode.id;
                var serie_id = this.projet.id;
                var duration =0;
                axios.post('/api/telechargements', {serie_id, qualiter, episode_id, duration})
                    .then(response => {
                        if(qualiter == 'hd'){
                            episode.downloadhd = true;
                        }
                        if(qualiter == 'fhd'){
                            episode.downloadfhd = true;
                        }
                        if(qualiter == 'dvd'){
                            episode.downloaddvd = true;
                        }
                    })
            },
            choix(info){
                if(info == 'precedent'){
                    this.currentItem--;
                }else if (info == 'suivant'){
                    this.currentItem++
                }
                this.$parent.titre = "Lecture de " + this.projet.titre + " " + this.playlist[this.currentItem].episode.type + " " + this.playlist[this.currentItem].episode.numero
            },
            filePdf(){
                this.src.then(pdf => {
                    this.numPages = pdf.numPages;
                });
            }
        },
        beforeMount(){
            this.getInfo(this.$route.params.type, this.$route.params.slug)
            this.$parent.titre = "chargements ..."
        },
        mounted(){

        }
    }
</script>
