<template>
        <div class="row ">
            <div class="col-md-12">
                <div class="row">
                    <ul class="list-unstyled">
                        <li class="media mb-2 bg-white p-2" style="box-shadow: 1px 1px 5px #555;"  v-for="(abonnement, key) in abonnements">
                            <img class="align-self-center mr-3" :src="abonnement.image" width="100px">
                            <div class="row media-body">
                                <div class="col-md-10">
                                    <h5 class="mt-0 mb-1">{{ abonnement.titre }}</h5>
                                    {{ abonnement.synopsis.slice(0, 150) }} ...
                                </div>
                                <div class="col-md-2 align-self-center text-center">
                                    <form method="POST" v-on:submit.prevent="Abonnement(abonnement.id, key)">
                                        <button  class="btn btn-link nostyle colorise p-0" type="submit">
                                            <h2><i class="fas fa-bell-slash  colorise"></i></h2>
                                        </button>
                                    </form>
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