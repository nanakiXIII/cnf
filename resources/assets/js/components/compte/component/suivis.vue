<template>
<div class="row" v-if="user.data != undefined">
    <div class="col-md-2 mb-4"  v-for="abo in user.data.suivis" v-if="user.data.suivis">
        <router-link :to="{ name: 'serieDetail', params: { type: encodeURI(abo.type) , slug: abo.slug} }" class="card-link">
            <div class="card bg-white">
                <img class="card-img-top" :src="abo.image" :alt="abo.titre" style="max-height: 220px">
                <div class="card-body">
                    <p class="card-text text-truncate text-center">{{abo.titre }}</p>
                </div>
            </div>
        </router-link>
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