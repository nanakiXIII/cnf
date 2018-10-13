<template>
    <div class="container mt-3">

        <div class="card mb-4">
            <div class="card-body">
                <h1 class="card-title">Dashboardss</h1>
                <p class="card-text">Hello , Welcome to your SPA dashboard</p>
            </div>
            <div class="row">
                <div class="card-columns">
                    <template v-for="abonnement in abonnements">
                        <div class="card card-news">
                            <router-link :to="{name:'compte', params: {id :abonnement.id}}">
                                <img class="card-img-top" :src="abonnement.image" alt="Card image cap">
                                <div class="card-body pb-0">
                                    <h5 class="card-title text-center"><b>{{ abonnement.titre }}</b></h5>
                                </div>
                            </router-link>
                            <button type="button" class="btn btn-outline-danger  btn-block">
                                Supprimer
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <passport-clients class="mb-4"></passport-clients>
        <passport-authorized-clients class="mb-4"></passport-authorized-clients>
        <passport-personal-access-tokens class="mb-4"></passport-personal-access-tokens>

    </div>
</template>

<script>
    export default {
        data(){
            return {
            }
        },
        computed: {

            abonnements(){
                return this.$store.getters.getSuivis;
            }
        },
        mounted(){
            this.$store.dispatch('SuivisRequest');
        }
    }
</script>
