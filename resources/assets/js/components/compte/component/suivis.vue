<template>
    <div class="row ">
        <div class="col-md-12">
            <div class="row">
                <ul class="list-unstyled" style="width:100%">
                    <li class="media mb-2 bg-white p-2 row" style="width:100%;box-shadow: 1px 1px 5px #555;"  v-for="(abonnement, key) in abonnements.abonnement">
                        <img class="align-self-center mr-3" :src="abonnement.image" width="100px">
                        <div class="row media-body">
                            <div class="col-md-8">
                                <router-link :to="{ name: 'serieDetail', params: {type: abonnement.type ,slug: abonnement.slug}}">
                                    <h5 class="mt-0 mb-1">
                                        {{ abonnement.titre }}
                                    </h5>
                                </router-link>
                                <div class="row col-md-12">
                                    <span v-bind:class="[type.full ? 'badge-success' : 'badge-secondary']" class="badge float-right mr-2" v-for="type in abonnement.typeFichier">
                                        {{ type.type }} {{ type.historique }} / {{ type.serie_count }}
                                    </span>
                                </div>
                                {{ abonnement.synopsis.slice(0, 150) }} ...
                            </div>
                            <div class="col-md-2 align-self-center text-center">
                                <form method="POST" v-on:submit.prevent="Abonnement(abonnement.id, key)">
                                    <button  class="btn btn-link nostyle colorise p-0" type="submit">
                                        <h2><i class="fas fa-bell-slash  colorise"></i></h2>
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-2 align-self-center text-center">
                                <button  class="btn btn-link nostyle p-0" style="color: grey" @click="abonnement.visible = true" v-if="!abonnement.visible">
                                    <h2><i class="fas fa-angle-down "></i></h2>
                                </button>
                                <button  class="btn btn-link nostyle p-0" style="color: grey" @click="abonnement.visible = false" v-if="abonnement.visible">
                                    <h2><i class="fas fa-angle-up"></i></h2>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12" v-if="abonnement.visible">
                            <ul class="list-group list-group-flush" v-for="episode in abonnement.fichier">
                                <li class="list-group-item "v-bind:class="[episode.verif ? 'list-group-item-success' : '']">
                                    <span v-if="episode.verif">
                                        <i class="fas fa-eye "></i> / <i class="fas fa-download "></i>
                                    </span>
                                    {{ episode.type }} {{ episode.numero }}: {{ episode.name }}
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="media mb-2 bg-white p-2" style="width:100%;box-shadow: 1px 1px 5px #555;" v-if="abonnements.abonnement ==''">
                        <div class="row media-body">
                            <div class="col-md-12 text-center">
                                Vous ne suivez pas de série pour le moment
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
                this.$delete(this.abonnements.abonnement, key)
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