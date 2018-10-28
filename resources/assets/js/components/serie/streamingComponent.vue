<template>
    <div>
        <div class="bg-img p-3">
            <div class="container relative p-4">
                <video-player  class="video-player-box"
                               style="width: 100%!important;"
                               ref="videoPlayer"
                               :options="playerOptions"
                               :playsinline="true"
                               customEventName="customstatechangedeventname"
                               @play="onPlayerPlay($event)"
                               @pause="onPlayerPause($event)"
                               @ended="onPlayerEnded($event)"
                               @timeupdate="onPlayerTimeupdate($event)"
                               @ready="playerReadied">
                </video-player>
                <div class="progress mt-3 mb-3 ml-5 mr-5">
                    <div class="progress-bar bg-success" role="progressbar" :style="progress" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-vue" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Vous avez déjà regardé cette vidéo</h4>

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body" v-if="serie.verif">
                        Date de visionnage: {{ serie.verif.updated_at | moment("dddd Do MMMM YYYY") }} <br>
                        Visionné: {{ convertSecondsToTime(serie.verif.time) }} / {{ convertSecondsToTime(duration)  }}
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-outline-primary" data-dismiss="modal" @click="reset">
                                <i class="fas fa-undo"></i>
                                Retour au début
                            </button>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-outline-success" data-dismiss="modal" @click="continu">
                                Continuer
                                <i class="fas fa-play-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'video.js/dist/video-js.css'
    import { videoPlayer } from 'vue-video-player'
    export default {
        components:{
            videoPlayer
        },
        data() {
            return {
                infos:'',
                log: false,
                action:'streaming',
                secure:'',
                serie_id:'',
                episode_id:'',
                time: '0',
                duration:'',
                etat:false,
                timeStatic:'',
                playerOptions: {
                    // videojs options
                    height: '400',
                    muted: false,
                    language: 'fr',
                    playbackRates: [0.7, 1.0, 1.5, 2.0],
                    sources: [{
                        type: "video/mp4",
                        src: "/storage/10.mp4"
                    }],
                    poster: "/static/images/author.jpg",
                }
            }
        },
        computed: {
            serie(){
                this.infos = this.$store.getters.getSerie;
                return this.infos
            },
            player() {
                return this.$refs.videoPlayer.player
            },
            progress:function(){
                return 'width: ' + ( Math.round((this.time*100) / this.duration ) ) + '%';
            },

        },
        watch: {
            infos() {
                this.$parent.titre = this.infos.titre
                this.serie_id = this.infos.id
                if (this.infos.getEpisode){
                    this.episode_id = this.infos.getEpisode.id
                }
                if(this.infos.verif && this.etat ){
                    if (this.infos.verif.time != undefined){
                        $('#modal-vue').modal('show');
                        this.player.currentTime(this.infos.verif.time)
                    }

                }
            },
            serie(){
                this.etat = true
            }
        },

        methods:{
            // listen event
            onPlayerPlay(player) {
                this.etat = "Play"
                this.streaming()
            },
            onPlayerPause(player) {
                this.etat = "Pause"
            },
            onPlayerEnded(player) {
                this.etat = "Fini !"
            },
            onPlayerTimeupdate(player) {
                this.time = player.currentTime()
                this.duration = player.duration();
                this.multiple(this.time);
            },
            playerReadied(player) {
                this.duration = player.duration();
                this.etat = "Ready"
            },
            convertSecondsToTime: function(time) {
                let seconds = Math.floor(time % 60);
                if (seconds < 10) seconds = '0' + seconds;
                let minutes = Math.floor(time / 60 % 60);
                return `${minutes}:${seconds}`
            },
            multiple:function (time) {
                let round = Math.round(time)
                if ( round % 10 == 0) {
                    if (round != this.secure){
                        this.streaming()
                        this.secure = round
                    }
                }
            },
            streaming: function () {
                const { episode_id, serie_id, action, time, log } = this;
                this.$store.dispatch('compteRequest', { episode_id, serie_id, action, time, log })

            },
            reset:function () {
                this.player.currentTime(0)
                this.time = 0
                this.player.play()
            },
            continu:function () {
                this.player.play()
            }

        },
        beforeMount(){
            let type = this.$route.params.type;
            let slug = this.$route.params.slug;
            let saison = this.$route.params.saison;
            let episode = this.$route.params.episode;
            this.type = type;
            this.slug = slug;
            this.saison = saison;
            this.episode = episode;
            if (this.$store.getters.isAuthenticated) {
                this.log = true;
            }
            if(!type){
                this.$router.push('/');
            }
            if(type === 'Animes' || type === 'Scantrad' || type === 'Light-Novel' || type === 'Visual-Novel'){
                if(episode && saison && slug){
                    console.log(saison)
                    if (this.$store.getters.isAuthenticated) {
                        this.log = true;
                        this.$store.dispatch('episodeRequestLog', {type, slug, saison,episode});
                    }else{
                        this.$store.dispatch('episodeRequest', {type, slug, saison,episode});
                    }

                }
                else{
                    this.$router.push('/');
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
