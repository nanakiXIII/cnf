<template>
    <div>
        <div class="container mt-3" v-show="info == true">
            <div class="card mb-3 shadow-sm p-2" v-for="d in data">
                <div class="row">
                    <div class="col-md-1">
                        <img :src="d.avatar" alt="" class="rounded-circle border align-middle" width="100%">
                    </div>
                    <div class="col-md-11">
                        <h3 class="border-bottom-1 text-capitalize" v-text="d.pseudo"></h3>
                        <div class="row">
                            <div class="col-md-3 mb-2" v-for="p in d.postes">
                                {{p.name}}
                                <template v-if="p.site == 'Animes'">des Animes</template>
                                <template v-if="p.site == 'Scantrad'">des Scans</template>
                                <template v-if="p.site == 'Visual-novel'">des Visual Novel</template>
                                <template v-if="p.site == 'Light-novel'">des Light Novel</template>
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
                data:{},
                info:false,
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
            getInfo(){
                axios.get('/api/equipe')
                    .then(response => {
                        this.info = true;
                        this.data = response.data.data
                    })
            },
        },
        beforeCreate(){
            this.$parent.titre = "Nos Equipes"
        },
        created(){
            this.getInfo();

        },
        mounted(){
        },
    }
</script>