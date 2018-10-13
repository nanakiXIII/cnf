<template>
    <div class="container mt-3">
        <div class="row mt-4 border bg-white" v-for="serie in series">
            <div class="col-md-4 img-news">
                <img :src="serie.image">
            </div>
            <div class="col-md-8 ">
                <div class="card-block px-1">
                    <a href="" class="title">
                        <h4 class="card-title pb-1 pt-3 center-mobile"><b>{{ serie.titre }}</b></h4>
                    </a>
                    <p class="card-text bg-grey col-md-12 p-3">
                        <span class='info-tooltip'>Auteur</span> {{ serie.auteur }}<br>
                        <span class='info-tooltip'>Studio</span> {{ serie.studio }}<br>
                        <span class='info-tooltip'>Année</span> {{ serie.annee }} <br>
                        <span class='info-tooltip'>Etat</span>
                        <br>
                        <span class='info-tooltip'>Genre(s)</span>

                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){

            return {

            }
        },
        props:{

        },
        watch:{
            '$route.params.type': function (type) {
            this.call(type)
    }
        },
        computed: {
            series(){
                return this.$store.getters.getSerie;
            }
        },
        methods:{
            call(){
                let type = this.$route.params.type;
                this.type = type;
                document.getElementById("TitreSection").innerHTML = "Liste des "+type;
                if(!type){
                    this.$router.push('/');
                }
                if(type === 'Animes' || type === 'Scantrad' || type === 'Light-Novel' || type === 'Visual-Novel'){
                    if (this.$store.getters.isAuthenticated) {
                        this.$store.dispatch('SerieRequestLog', {type});
                    }
                    else {
                        this.$store.dispatch('SerieRequest', {type});
                    }
                }
                else{
                    this.$router.push('/');
                }
            }

        },
        mounted(){
            this.call()
        },
    }
</script>
