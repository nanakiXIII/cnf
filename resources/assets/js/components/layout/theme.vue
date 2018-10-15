<template>

<div>
    <router-view></router-view>
</div>



</template>

<script>
    export default {
        data(){
            return {
                titre:'Accueil'
            }
        },
        watch:{
            titre(){
                this.$parent.titre = this.titre
            }
        }
    }
</script>
