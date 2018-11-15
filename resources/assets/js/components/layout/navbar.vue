<template>
    <span>
        <nav class="navbar navbar-expand-lg navbar-dark bg-nav big-nav sticky-top">
            <div class="container">
                <router-link :to="{ name:'accueil' }" class="navbar-brand">
                    <img data-src="/img/logo.png"  width="120px" alt="Chuushin no Fansub" class="lazyload">
                </router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <router-link title="Home" :to="{ name:'accueil' }" class="nav-link">Accueil <span class="sr-only">(current)</span></router-link>
                        </li>
                        <li class="nav-item text-white">
                            <a class="nav-link" title="Equipes" href="#">Equipes</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" title="Nos Projets" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Nos Projets
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <router-link title="Animes" :to="{ name: 'serie', params: { type: 'Animes' }}" class="dropdown-item" >Animés</router-link>
                                <router-link title="Scantrad" :to="{ name: 'serie', params: { type: 'Scantrad' }}" class="dropdown-item" >Scantrad</router-link>
                                <router-link title="Light novel" :to="{ name: 'serie', params: { type: 'Light-Novel' }}" class="dropdown-item" >Light-Novel</router-link>
                                <router-link title="Visual novel" :to="{ name: 'serie', params: { type: 'Visual-Novel' }}" class="dropdown-item" >Visual-Novel</router-link>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" title="Contact" href="#">Nous Contacter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" title="Partenaires" href="#">Partenaires</a>
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
                                <router-link title="Administration" :to="{ name: 'administration' }" v-if="this.found(user.permission,'Administration')" class="btn btn-outline-warning col-auto mr-auto" >Administration</router-link>
                            </span>


                        <a class="btn btn-outline-danger col-auto mr-auto" title="Déconnexion" @click.prevent="logout">Déconnexion</a>
                    </div>
                    <div v-else>
                        <router-link title="Login" :to="{ name: 'login' }" class="btn btn-outline-success col-auto mr-auto">Se Connecter</router-link>
                        <router-link title="Register" :to="{ name: 'register' }" class="btn btn-outline-primary col-auto mr-auto">S'enregistrer</router-link>
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
