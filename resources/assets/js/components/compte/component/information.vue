<template>
    <div class="row" v-if="user.data != undefined">
        <div class="col-md-12" v-show="user.data.telechargement.length > 0">
            <ul class="list-unstyled" style="width:100%">
                <li class="media mb-2 bg-white p-2" style="width:100%;"  v-for="episode in user.data.telechargement">
                    <div class="col-md-2 no-mobile text-center">
                        <img class="align-self-center mr-3" :src="episode.episode.image" style="max-height: 88px">
                    </div>
                    <div class="row media-body">
                        <div class="col-md-10">
                            <h5 class="mt-0 mb-1 colorise">
                                {{ episode.serie.titre }}
                            </h5>
                            {{ episode.saison.type }} {{ episode.saison.numero }}: {{ episode.episode.type }} {{ episode.episode.numero }}: {{ episode.episode.name }} <br>
                            Téléchargé {{ episode.created_at.date | moment('from', 'now') }}
                        </div>
                        <div class="col-md-2 align-self-center text-center">

                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {

            }
        },
        props:['user'],
        computed: {

        },
        methods: {


        },
        mounted(){

        }
    }
</script>