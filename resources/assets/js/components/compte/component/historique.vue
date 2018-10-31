<template>
    <div class="row ">
        <div class="col-md-12">
            <div class="row">
                <ul class="list-unstyled" style="width:100%">
                    <li class="media mb-2 bg-white p-2" style="width:100%;box-shadow: 1px 1px 5px #555;"  v-for="(historique, key) in abonnements.historique">
                        <img class="align-self-center mr-3" src="http://placehold.jp/1280x720.png" width="100px">
                        <div class="row media-body">
                            <div class="col-md-10">
                                <h5 class="mt-0 mb-1">
                                    {{ historique.titre }}
                                </h5>
                                {{ historique.saison_type }} {{ historique.saison_numero }}: {{ historique.type }} {{ historique.numero }}: {{ historique.name }} <br>
                                {{ historique.updated_at | moment('from', 'now') }}
                            </div>
                            <div class="col-md-2 align-self-center text-center">
                                <router-link :to="{name:'streaming', params:{type: historique.serie_type, slug: historique.serie_slug, saison: historique.saison_id, episode:historique.episode_id}}"  class="btn btn-link nostyle colorise p-0" v-if="historique.qualite == 'vue'">
                                    <h2><i class="fas fa-eye  colorise"></i></h2>
                                </router-link>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                'id': '',
                'action':'Abonnement',
                'abo': true
            }
        },
        computed: {
            abonnements(){
                return this.$store.getters.getSuivis;
            }
        },
        methods: {
            found: function (tab, element) {
                let response = tab.indexOf(element)
                if (response >= 0){
                    return true
                }
                else{
                    return false
                }
            },
            Abonnement: function (serie_id, key) {
                this.id = serie_id;
                this.$delete(this.abonnements, key)
                const { abo, id ,action } = this;

                this.$store.dispatch('compteRequest', { abo, serie_id, action })
                    .then(() => {

                    })
            }
        },
        mounted(){
            this.$store.dispatch('SuivisRequest');
        }
    }
</script>