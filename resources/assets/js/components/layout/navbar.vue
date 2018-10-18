<template>
    <span>
        <nav class="navbar navbar-expand-lg navbar-dark bg-nav big-nav sticky-top">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="/img/logo.png"  width="120px" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Accueil <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item text-white">
                            <a class="nav-link" href="#">Equipes</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Nos Projets
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <router-link :to="{ name: 'serie', params: { type: 'Animes' }}" class="dropdown-item" >Animés</router-link>
                                <router-link :to="{ name: 'serie', params: { type: 'Scantrad' }}" class="dropdown-item" >Scantrad</router-link>
                                <router-link :to="{ name: 'serie', params: { type: 'Light-Novel' }}" class="dropdown-item" >Light-Novel</router-link>
                                <router-link :to="{ name: 'serie', params: { type: 'Visual-Novel' }}" class="dropdown-item" >Visual-Novel</router-link>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Nous Contacter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Partenaires</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <nav class="navbar navbar-light bg-white border-bottom-1">
            <div class="container">
                <span class="navbar-brand mb-0 h1 barre" >{{ titre}}</span>
                <div class="pull-right">
                    <div v-if="user.name">
                        <router-link :to="{ name: 'dashboard' }" class="btn btn-outline-success col-auto mr-auto">{{ user.name }}</router-link>
                            <span v-if="user.permission">
                                <a v-if="this.found(user.permission,'Administration')" href="#" class="btn btn-outline-warning col-auto mr-auto" >Administration</a>
                            </span>


                        <a class="btn btn-outline-danger col-auto mr-auto" @click.prevent="logout">Déconnexion</a>
                    </div>
                    <div v-else>
                        <router-link :to="{ name: 'login' }" class="btn btn-outline-success col-auto mr-auto">Se Connecter</router-link>
                        <router-link :to="{ name: 'register' }" class="btn btn-outline-primary col-auto mr-auto">S'enregistrer</router-link>
                    </div>
                </div>
            </div>
        </nav>
    </span>
</template>



<script>
    export default {
        data(){
            return {

            }
        },
        props:{
            titre : String
        }

        ,
        watch:{
            titre(){
            }
        },
        computed: {
            user(){
                return this.$store.getters.getProfile;
            }
        },
        methods: {
            logout: function () {
                this.$store.dispatch('authLogout')
                    .then(() => {
                        this.$router.go(0)
                    })
            },
            setMessage(msg) {
                this.msg = msg;
            },
            found: function (tab, element) {
                let response = tab.indexOf(element)
                if (response >= 0){
                    return true
                }
                else{
                    return false
                }
            }
        }
    }
</script>
