<template>
    <div>
        <div class="bg-img p-3">
            <div class="container relative p-4">
                <div class="row">
                    <div class="col-md-8" v-show="player != null">
                        <video ref="videoPlayer" class="video-js"
                               @timeupdate="onPlayerTimeupdate($event)"
                               @play="onPlayerPlay($event)"
                               >

                        </video>
                    </div>
                    <div class="col-md-4">
                        <div class="list-group" v-if="playlist.length != 0">
                            <button type="button"
                                    v-for="p in playlist"
                                    v-bind:class="[ p.id == playlist[currentItem].id ? 'active' : 'bg-white']"
                                    class="list-group-item list-group-item-action"
                                    @click="choix(p.playlistItemId_ -1)">
                                {{p.episode.type }} {{p.episode.numero }}: {{p.episode.name }}
                            </button>
                        </div>
                        <div v-else="">
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

        <div class="container mt-3">
            <div class="row " v-if="playlist.length != 0">
                <div class="col-md-12" v-if="playlist[currentItem].episode.stream && playlist[currentItem].episode.time > 30">
                    <div class="alert alert-secondary" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Reprendre la lecture à <b>{{convertSecondsToTime(playlist[currentItem].episode.time)}}</b>
                        <div class="pull-right">
                            <button type="button" class="btn btn-success btn-sm" @click="reprendre()">Reprendre</button>
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <button type="button"
                            class="btn btn-outline-secondary btn-lg btn-block"
                            @click="choix('precedent')"
                            v-if="player.playlist.currentIndex() != 0">
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
                            v-if="player.playlist.currentIndex() != player.playlist.lastIndex()">
                        Suivant
                    </button>
                </div>
                <div class="col-md-12" v-show="episodes.name">
                    <div class="media mt-3 border shadow-sm p-3 bg-white rounded">
                        <img class="align-self-center mr-3" :src="playlist[currentItem].episode.image" alt="" width="150px">
                        <div class="media-body">
                            <h5 class="mt-1">{{ playlist[currentItem].episode.type }} {{ playlist[currentItem].episode.numero }}: {{ playlist[currentItem].episode.name }}</h5>
                            <p>
                            <div class="pull-right">
                                <a v-show="playlist[currentItem].episode.dvd != 'non'" :href="playlist[currentItem].episode.dvd" @click="dl('dvd',playlist[currentItem].episode)" class="btn" v-bind:class="[ playlist[currentItem].episode.downloaddvd ? 'btn-success' : 'btn-secondary']" target="_blank">DVD</a>
                                <a v-show="playlist[currentItem].episode.hd != 'non'"  :href="playlist[currentItem].episode.hd" @click="dl('hd', playlist[currentItem].episode)" class="btn" v-bind:class="[ playlist[currentItem].episode.downloadhd ? 'btn-success' : 'btn-secondary']" target="_blank">720P</a>
                                <a v-show="playlist[currentItem].episode.fhd != 'non'" :href="playlist[currentItem].episode.fhd" @click="dl('fhd',playlist[currentItem].episode)" class="btn" v-bind:class="[ playlist[currentItem].episode.downloadfhd ? 'btn-success' : 'btn-secondary']" target="_blank">1080P</a>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'video.js/dist/video-js.css'
    import videojs from 'video.js'
    import * as playlist from 'videojs-playlist'
    export default {
        data() {
            return {
                info:'',
                projet:{},
                saisons: {},
                episodes:{},
                player: null,
                options: {
                    autoplay: false,
                    controls: true,
                    responsive:true,
                    height: '500',
                },
                playlist:[],
                currentItem:0,
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
                        this.currentItem = video

                    }
                    if(episode.etat >= 4){
                        this.playlist.push({
                            sources: [{
                                src: "/storage/serie/"+this.projet.id+"/"+this.saisons.id+'/'+episode.id+"/"+episode.id+".mp4",
                                type: 'video/mp4'
                            }],
                            poster: episode.image,
                            id:episode.id,
                            episode: episode
                        })
                    }

                    video++
                });
                this.init()
            },
            ready(){
                console.log(this.player.duration());
            }

        },

        methods:{
            reprendre(){
                this.player.currentTime(this.playlist[this.currentItem].episode.time)
                this.player.play();
            },
            convertSecondsToTime: function(time) {
                let seconds = Math.floor(time % 60);
                if (seconds < 10) seconds = '0' + seconds;
                let minutes = Math.floor(time / 60 % 60);
                return `${minutes}:${seconds}`
            },
            getInfo(type, slug){
                axios.get('/api/projets/'+type+'/'+slug)
                    .then(response => {
                        this.projet = response.data.data
                    })
            },
            onPlayerPlay(player) {
                this.dl('vue', this.playlist[this.currentItem].episode)
            },
            onPlayerTimeupdate(player) {
                this.multiple(this.player.currentTime());
                console.log(this.player.duration());
            },
            multiple:function (time) {
                let round = Math.round(time)
                if ( round % 30 == 0) {
                    if (round != this.secure){
                        this.dl('vue', this.playlist[this.currentItem].episode)
                    }
                }
            },
            choix(info){
                if(info == 'precedent'){
                    this.player.playlist.previous();
                }else if (info == 'suivant'){
                    this.player.playlist.next();
                }
                else{
                    this.player.playlist.currentItem(info);
                }
                this.currentItem = this.player.playlist.currentIndex()
                this.$parent.titre = "Lecture de " + this.projet.titre + " " + this.playlist[this.currentItem].episode.type + " " + this.playlist[this.currentItem].episode.numero
            },
            dl:function(qualiter, episode){
                var episode_id = episode.id;
                var serie_id = this.projet.id;
                var duration =this.player.currentTime();
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
            init(){
                this.player = videojs(this.$refs.videoPlayer, this.options, () => {
                    console.log("duratin", this.player.duration());
                })
                this.player.playlist(this.playlist,this.currentItem);
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
