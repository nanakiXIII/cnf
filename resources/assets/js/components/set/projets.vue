<template>
    <section class="fond">
        <div class="container" style="min-height: 1200px">
            <div class="col-md-12 text-center mt-5">
                <h1>
                    <b>{{serie.titre}}</b>
                </h1>
            </div>
            <div class="col-md-12">
                <div class="synopsis">
                    {{serie.synopsis}}
                </div>
                <div class="row seed_projet_images">
                    <div class="col-md-3">
                        <img src="https://picsum.photos/200/150" alt="" width="100%" height="150px">
                    </div>
                    <div class="col-md-3">
                        <img src="https://picsum.photos/200/150" alt="" width="100%" height="150px">
                    </div>
                    <div class="col-md-3">
                        <img src="https://picsum.photos/200/150" alt="" width="100%" height="150px">
                    </div>
                    <div class="col-md-3">
                        <img src="https://picsum.photos/200/150" alt="" width="100%" height="150px">
                    </div>
                </div>
            </div>
            <div class="col-md-12 cate">
                <h3 class="categorie">Fiche Technique</h3>
            </div>
            <div class="col-md-12 pl-5 pr-5" v-if="serie.saisons">
                <b>Type </b>: {{serie.saisons[0].episodes[0].type}} <i>//</i> <b>Année</b> : {{ serie.annee | moment('YYYY') }} <i>//</i>
                <b>Genres</b> : <span v-for="genre in serie.genres"> {{genre.name}}</span> <i>//</i> <b>Statut</b> :
                <span v-if="serie.etat == 1">Terminé ({{serie.episodes}} / {{serie.episode}})</span>
                <span v-if="serie.etat == 3">Licencié</span>
                <span v-if="serie.etat == 2">Arrêté ({{serie.episodes}} / {{serie.episode}})</span>
            </div>
            <div class="col-md-12  pl-5 pr-5" v-if="serie.saisons">
                <b>Studio </b>: {{serie.studio}} <i>//</i> <b>Version(s)</b> :
                <span v-if="serie.saisons[0].episodes[0].hd == 'non'">HD</span>
                <span v-if="serie.saisons[0].episodes[0].fhd == 'non'">FHD</span>
            </div>

        </div>

    </section>

</template>
<script>
    export default {
        data(){
            return {
                serie:{}
            }
        },
        methods: {
            getInfo(type, slug) {
                axios.get('/api/projets/' + type + '/' + slug)
                    .then(response => {
                        this.serie = response.data.data
                        this.$parent.titre = response.data.data.titre
                    })
            },
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