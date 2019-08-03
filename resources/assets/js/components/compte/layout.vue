<template>
    <div>
        <div class="bg-img p-3">
            <div class="container p-4">
                <div class="row">
                    <transition name="custom-classes-transition" enter-active-class="animated fadeInLeft">
                        <div class="col-md-3" v-show="show && user.data.avatar != 'noAvatar'" v-if="user.data">
                            <img class="rounded-circle" :src="user.data.avatar" style="max-height: 200px">
                        </div>
                    </transition>
                    <transition name="custom-classes-transition" enter-active-class="animated slideInUp">
                        <div class="col-md-9 bg-grey p-3 mt-2 shadow rounded" v-show="show" v-if="user.data">
                            <h3 class="text-center">Informations</h3>
                            Pseudo: <b>{{user.data.name}}</b><br>
                            Email: <b>{{user.data.email}}</b> <br>
                            Membre depuis <b>{{ user.data.created_at.date | moment("from", true)}}</b> <br>
                            <div class="row mt-4">
                                <div class="offset-md-1 col-md-2 text-center">
                                    <h4 class="colorise">
                                        <i class="fas fa-bookmark"></i><b> {{user.data.suivis.length}}</b>
                                    </h4>
                                </div>
                                <div class="col-md-2 text-center">
                                    <h4 class="colorise">
                                        <i class="fas fa-file-download"></i> {{user.data.telechargement.length}}
                                    </h4>
                                </div>
                                <div class="col-md-2 text-center">
                                    <h4 class="colorise">
                                        <i class="fas fa-play-circle"></i><b> {{user.data.visionnage.length}}</b>
                                    </h4>
                                </div>
                                <div class="col-md-2 text-center">
                                    <h4 class="colorise">
                                        <i class="fas fa-book-reader"></i><b> {{user.data.lecture.length}}</b>
                                    </h4>
                                </div>
                                <div class="col-md-2 text-center">
                                    <h4 class="colorise">
                                        <i class="fas fa-trophy"></i><b> 0</b>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
        <div class="container">
            <nav class="nav nav-pills nav-justified border">
                        <router-link :to="{name:'dashboard'}" class="nav-item nav-link text-truncate">
                            <i class="fas fa-bookmark"></i><b> Abonnements</b>
                        </router-link>
                        <router-link :to="{name:'userInformation'}" class="nav-item nav-link text-truncate">
                            <i class="fas fa-file-download"></i><b> Téléchargements</b>
                        </router-link>
                        <router-link :to="{name:'historique'}" class="nav-item nav-link text-truncate">
                            <i class="fas fa-play-circle"></i><b> Streaming</b>
                        </router-link>
                        <router-link :to="{name:'parametre'}" class="nav-item nav-link text-truncate">
                            <i class="fas fa-book-reader"></i><b> Lecture en ligne</b>
                        </router-link>
                        <router-link :to="{name:'parametre'}" class="nav-item nav-link text-truncate disabled">
                            <i class="fas fa-trophy"></i><b> Trophées</b>
                        </router-link>
                        <router-link :to="{name:'parametre'}" class="nav-item nav-link text-truncate">
                            <i class="fas fa-cog  "></i><b> Paramètres</b>
                        </router-link>
                    </nav>
            <div class="row mt-3">
                <div class="col-md-12">
                    <router-view :user="user"></router-view>
                </div>

            </div>
        </div>
    </div>



</template>
<script>
    export default {
        data(){
            return {
                theme:'',
                show:false
            }
        },
        props:['user'],
        computed: {

        },
        watch:{
          theme(){
              this.$parent.theme = this.theme;
          },
        },
        methods: {
        },
        mounted(){
            this.$parent.titre = "Mon compte";
            this.show =true;
        }
    }
</script>