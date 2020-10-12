<template>
    <section class="fond">
        <div class="container" style="min-height: 1200px">
            <div class="col-md-12 text-center mt-5">
                <h1>
                    <b>Projets & Téléchargements</b>
                </h1>
            </div>
            <div class="row col-md-12 mt-5">
                <div class="col-md-12">
                    <h2 class="dl_sig bg-success"><b>Terminé</b></h2>
                </div>
                <router-link :to="{name:'seedteam_projets', params: { type: encodeURI(serie.type) , slug: serie.slug}}" class="col-md-3 seed_projet" v-for="serie in series" :key="serie.id" v-if="serie.etat === 1">
                    <img :src="serie.image" alt="" style="margin-top: -40px">
                    <div class="seed_projet_over">
                        <div class="seed_projet_title">
                            {{serie.titre}} <br>
                            <small v-if="serie.etat != 3">{{serie.episode}} / {{serie.episode}}</small>
                        </div>

                    </div>
                </router-link>
            </div>
            <div class="row col-md-12">
                <div class="col-md-12">
                    <h2 class="dl_sig" style="background-color: #e18c31"><b>Licencié</b></h2>
                </div>
                <router-link :to="{name:'seedteam_projets', params: { type: encodeURI(serie.type) , slug: serie.slug}}" class="col-md-3 seed_projet" v-for="serie in series" :key="serie.id" v-if="serie.etat === 3">
                    <img :src="serie.image" alt="" style="margin-top: -40px">
                    <div class="seed_projet_over">
                        <div class="seed_projet_title">
                            {{serie.titre}}
                        </div>

                    </div>
                </router-link>
            </div>
            <div class="row col-md-12">
                <div class="col-md-12">
                    <h2 class="dl_sig" style="background-color: #af3330;"><b>Arrêté</b></h2>
                </div>
                <router-link :to="{name:'seedteam_projets', params: { type: encodeURI(serie.type) , slug: serie.slug}}" class="col-md-3 seed_projet" v-for="serie in series" :key="serie.id" v-if="serie.etat === 2">
                    <img :src="serie.image" alt="" style="margin-top: -40px">
                    <div class="seed_projet_over">
                        <div class="seed_projet_title">
                            {{serie.titre}} <br>
                            <small v-if="serie.etat != 3">{{serie.episode}} / {{serie.episode}}</small>
                        </div>

                    </div>
                </router-link>
            </div>
        </div>

    </section>

</template>

<script>
    export default {

        data(){
            return {
                series:{}
            }
        },
        methods:{
            getInfo(){
                axios.get('/api/seedteam')
                    .then(response => {
                        this.series = response.data.data
                    })
            },
        },
        mounted() {
            this.$parent.titre = "SeedTeam"
            this.getInfo()
        }


    }
</script>
