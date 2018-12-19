<template>
    <div class="container mt-5">
        <div id="accordion ">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link no-decoration" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Gestions des nouveautés
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <router-link :to="{name:'AdminNews'}" class="col-md-2 text-center no-decoration">
                                <h1><i class="fas fa-users"></i></h1>
                                <small>News</small>
                            </router-link>
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link no-decoration" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Gestions des Séries
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-link no-decoration" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <i class="fas fa-users"></i> Gestion des utilisateurs
                        </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                    <div class="card-body">
                        <div class="row">
                            <router-link :to="{name:'utilisateurs'}" class="col-md-2 text-center no-decoration">
                                <h1><i class="fas fa-users"></i></h1>
                                <small>Utilisateurs</small>
                            </router-link>
                            <router-link :to="{name:'AdminPostes'}" class="col-md-2 text-center no-decoration">
                                <h1><i class="fas fa-user-plus"></i></h1>
                                <small>Postes</small>
                            </router-link>
                            <router-link :to="{name:'AdminPermissions'}" class="col-md-2 text-center no-decoration">
                                <h1><i class="fas fa-user-shield"></i></h1>
                                <small>Permissions</small>
                            </router-link>
                            <router-link :to="{name:'AdminRoles'}" class="col-md-2 text-center no-decoration">
                                <h1><i class="fas fa-user-tag"></i></h1>
                                <small>Rôles</small>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-folder"></i> Gestion des séries
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <router-link :to="{name:'AdminSerieADD'}" class="col-md-6 text-center text-success no-decoration mb-2">
                                <h1 class="mb-0"><i class="fas fa-folder-plus"></i></h1>
                                <small>Ajouter une Série</small>
                            </router-link>
                            <router-link :to="{name:'AdminSerie'}" class="col-md-6 text-center text-info no-decoration mb-2">
                                <h1 class="mb-0"><i class="fas fa-folder-open"></i></h1>
                                <small>Liste des Série</small>
                            </router-link>
                            <router-link :to="{name:'AdminPermissions'}" class="col-md-6 text-center text-info no-decoration mb-2">
                                <h1 class="mb-0"><i class="fas fa-tags"></i></h1>
                                <small>Gestion des Genres</small>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-users"></i> Gestion des utilisateurs
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <router-link :to="{name:'utilisateurs'}" class="col-md-6 text-center text-info no-decoration mb-2">
                                <h1 class="mb-0"><i class="fas fa-users"></i></h1>
                                <small>Utilisateurs</small>
                            </router-link>
                            <router-link :to="{name:'AdminPostes'}" class="col-md-6 text-center text-info no-decoration mb-2">
                                <h1 class="mb-0"><i class="fas fa-user-plus"></i></h1>
                                <small>Postes</small>
                            </router-link>
                            <router-link :to="{name:'AdminPermissions'}" class="col-md-6 text-center text-info no-decoration mb-2">
                                <h1 class="mb-0"><i class="fas fa-user-shield"></i></h1>
                                <small>Permissions</small>
                            </router-link>
                            <router-link :to="{name:'AdminRoles'}" class="col-md-6 text-center text-info no-decoration mb-2">
                                <h1 class="mb-0"><i class="fas fa-user-tag"></i></h1>
                                <small>Rôles</small>
                            </router-link>
                        </div>
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
                from:'1',
                contenu:{},
                page:1,
                lastPage:false,
                nextPage:1
            }


        },
        computed: {
            news(){
                return this.$store.getters.getNews;
            },
            user(){
                return this.$store.getters.getProfile
            }
        },
        watch:{
            news(){
                this.page=this.news.pagination.current_page
                this.lastPage=this.news.pagination.last_page
                this.nextPage=this.news.pagination.current_page +1
                this.from = this.news.pagination.from +10
                this.contenu = Object.assign(this.data, this.news.data)
            }
        },
        methods: {
            next(){
                this.paginate()
            },
            paginate(){
                const { nextPage, page, from } = this
                this.$store.dispatch('NewsRequest', {nextPage, from});
            },
        },
        mounted(){
            this.$parent.titre = "Administration"
            this.paginate()

        }
    }
</script>