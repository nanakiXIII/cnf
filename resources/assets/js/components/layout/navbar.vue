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
                            <router-link title="Home" :to="{ name:'accueil' }" class="nav-link">Accueil</router-link>
                        </li>
                        <li class="nav-item text-white">
                            <router-link class="nav-link" title="Equipes" :to="{ name:'equipes' }">Equipes</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" title="Equipes" :to="{ name: 'projets'}" >Nos Projets</router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" title="Contact" :to="{ name: 'contact'}">Nous Contacter</router-link>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" title="Mon compte" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <template v-if="!user && sync == false">Mon Compte</template>
                                <template v-if="user && !user.data">
                                    <div class="spinner-grow" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </template>
                                <template v-if="user && user.data && sync == false">{{user.data.name}}</template>

                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <router-link title="Login" v-if="!user" :to="{ name: 'login'}" class="dropdown-item" >Se Connecter</router-link>
                                <router-link title="Register" v-if="!user" :to="{ name: 'register'}" class="dropdown-item" >S'enregistrer</router-link>
                                <router-link title="Mon profile" v-if="user && user.data" :to="{ name: 'dashboard'}" class="dropdown-item" >Mon Profil</router-link>
                                <router-link title="Register" v-if="user && user.data && this.found(user.data.permission,'Administration')" :to="{ name: 'administration'}" class="dropdown-item" >Administration</router-link>
                                <a title="Logout" href="#" v-if="user && user.data"  class="dropdown-item" @click.prevent="logout" >Déconnexion</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <nav class="navbar navbar-light bg-white border-bottom-1">
            <div class="container">
                <button type="button" @click="precedent()" class="btn btn-outline-colorise">Précédent</button>
                <span class="navbar-brand mb-0 h1 barre no-mobile" >{{ titre}}</span>
                <div class="pull-right">
                    <div class="btn-group btn-group-justified" v-if="$route.name == 'projets'" role="group" aria-label="Recherche des types">
                                <div class="btn-group" role="group">
                                    <button type="button"
                                            class="btn btn-default"
                                            v-bind:class="{'colorise':(type === 'all')}"
                                            @click="type = 'all'"
                                    >
                                        <i class="fas fa-globe"></i> <template class="no-mobile">All</template>
                                    </button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                            class="btn btn-default"
                                            v-bind:class="{'colorise':(type === 'Animes')}"
                                            @click="type = 'Animes'"
                                    >
                                        <i class="fas fa-video"></i> <template class="no-mobile">Animés</template>
                                    </button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                            class="btn btn-default"
                                            v-bind:class="{'colorise':(type === 'Scantrad')}"
                                            @click="type = 'Scantrad'"
                                    >
                                        <i class="fas fa-paint-brush"></i> <template class="no-mobile">Scantrad</template></button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                            class="btn btn-default"
                                            v-bind:class="{'colorise':(type === 'Light-Novel')}"
                                            @click="type = 'Light-Novel'"
                                    >
                                        <i class="fas fa-book-open"></i> <template class="no-mobile">Light Novel</template>
                                    </button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                            class="btn btn-default"
                                            v-bind:class="{'colorise':(type === 'Visual-Novel')}"
                                            @click="type = 'Visual-Novel'"
                                    >
                                        <i class="fas fa-gamepad"></i> <template class="no-mobile">Visual Novel</template>
                                    </button>
                                </div>
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
                type:'all'
            }
        },
        props:{
            titre : String,
            sync : Boolean,
        }

        ,
        watch:{
            type(){
                this.$parent.type = this.type
            }
        },
        computed: {
            user(){
                if (this.$store.getters.isAuthenticated) {
                    return this.$store.getters.getProfile
                }
            }
        },
        methods: {
            precedent(){
                this.$router.go(-1)
            },
            logout: function () {
                this.$store.dispatch('authLogout')
                    .then(() => {
                        this.$router.push('/')
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
