<template>

    <div class="container mt-5">
        <div class="bg-white p-4 mb-3">

            Statistique des visiteurs et des téléchargements

        </div>
        <div class="bg-white p-4 mb-3">
            Espace disponible
            <div class="progress">
                <div class="progress-bar" role="progressbar" :style="'width:'+ data.DDPourcentage +'%;'" :aria-valuenow="data.DDPourcentage" aria-valuemin="0" aria-valuemax="100">
                    {{ data.DDLibre }} {{data.DDUniter}} / {{ data.DDTotal }} {{data.DDUniter}}
                </div>
            </div>
            Ram
            <div class="progress">
                <div class="progress-bar" role="progressbar" :style="'width:'+ data.memoryPourcentage +'%;'" :aria-valuenow="data.memoryPourcentage" aria-valuemin="0" aria-valuemax="100">
                    {{ data.memoryUtil }} / {{ data.memoryTotal }}
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
                            <router-link :to="{name:'AdminNews'}" class="col-md-2 text-center no-decoration">
                                <h1><i class="fas fa-users"></i></h1>
                                <small>News</small>
                            </router-link>
                            <router-link :to="{name:'AdminSerieADD'}" class="col-md-6 text-center text-success no-decoration mb-2">
                                <h1 class="mb-0"><i class="fas fa-folder-plus"></i></h1>
                                <small>Ajouter une Série</small>
                            </router-link>
                            <router-link :to="{name:'AdminSerie'}" class="col-md-6 text-center text-info no-decoration mb-2">
                                <h1 class="mb-0"><i class="fas fa-folder-open"></i></h1>
                                <small>Liste des Série</small>
                            </router-link>
                            <router-link :to="{name:'AdminGenres'}" class="col-md-6 text-center text-info no-decoration mb-2">
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
            user(){
                return this.$store.getters.getProfile
            }
        },
        watch:{

        },
        methods: {
            getInfo(){
                axios.get('/api/administration/dashboard')
                    .then(response => {
                        this.data = response.data
                        this.data.DDPourcentage = (1-( this.data.DDLibre / this.data.DDTotal )) *100;
                    })

            },
        },
        mounted(){
            this.$parent.titre = "Administration"
            this.getInfo()
        }
    }
</script>