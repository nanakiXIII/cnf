<template>

<div>
    <router-view :type="type"></router-view>
</div>



</template>

<script>
    export default {
        data(){
            return {
                titre:'Accueil',
            }
        },
        props:{
           type : String,
        },
        watch:{
            titre(){
                this.$parent.titre = this.titre
            },
            type(){

            }
        }
    }
</script>
