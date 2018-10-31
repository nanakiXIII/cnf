<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-4">
                <ul class="list-group">
                    <router-link :to="{name:'dashboard'}">
                        <li class="list-group-item">
                            <i class="fas fa-bookmark "></i> Suivis
                        </li>
                    </router-link>
                    <router-link :to="{name:'historique'}">
                        <li class="list-group-item">
                            <i class="fas fa-history "></i> Historique
                        </li>
                    </router-link>
                    <!--<router-link :to="{name:'success'}">
                        <li class="list-group-item">
                            <i class="fas fa-trophy  "></i> Success
                        </li>
                    </router-link>-->
                    <router-link :to="{name:'parametre'}">
                        <li class="list-group-item">
                            <i class="fas fa-cog  "></i> Paramètres
                        </li>
                    </router-link>
                    <!--<router-link :to="{name:'informations'}">
                        <li class="list-group-item">
                            <i class="fas fa-user  "></i> Mes Informations
                        </li>
                    </router-link>
                    <router-link :to="{name:'api'}">
                        <li class="list-group-item">
                            <i class="fas fa-user  "></i> API
                        </li>
                    </router-link>-->
                </ul>
            </div>
            <div class="col-md-8">
                <router-view></router-view>
            </div>
        </div>
    </div>


</template>
<script>
    export default {
        data(){
            return {
                theme:''
            }
        },
        computed: {

        },
        watch:{
          theme(){
              this.$parent.theme = this.theme;
          }
        },
        methods: {
        },
        mounted(){
            this.$parent.titre = "Mon compte";
        }
    }
</script>