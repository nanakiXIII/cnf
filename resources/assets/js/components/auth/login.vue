<template>
    <div class="container">
        <div class="alert alert-danger mt-3" role="alert" v-if="authErrors.has('email') || authErrors.has('invalid_credentials')">
            <div v-text="authErrors.get('invalid_credentials')"></div>
            <div v-text="authErrors.get('email')"></div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="auth-form text-center">

                            <form method="POST" v-on:submit.prevent="login">
                                <div class="form-group row">
                                    <label for="email" class="sr-only">Identifiant</label>
                                    <input type="email" id="email" class="form-control" placeholder="Identifiant" required autofocus v-model="email">
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="sr-only">Mot de passe</label>
                                    <input type="password" id="password" class="form-control" placeholder="Mot de passe" required v-model="password">
                                </div>
                                <div class="checkbox mt-2">
                                    <label>
                                        <input type="checkbox" value="remember" v-model="remember"> Se souvenir de moi ?
                                    </label>
                                </div>

                                <button class="btn btn-outline-info btn-block mt-2 mb-2" type="submit">Connexion</button>

                                <router-link class="p-2" :to="{ name: 'password-email' }">Mot de passe oublié</router-link>

                                <p class="p-2">Pas inscrit ? <router-link :to="{ name: 'register' }">Créer un compte</router-link></p>
                            </form>
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
                'email':'',
                'password':'',
                'remember':false,
            }
        },
        computed: {
            authErrors(){
                return this.$store.getters.authErrors;
            }
        },
        methods: {
            login: function () {
                const { email, password, remember } = this;
                this.$store.dispatch('authRequest', { email, password, remember })
                    .then(() => {
                        this.$router.go(-1)
                    })
            }
        },
        mounted(){
            this.$parent.titre = "Connexion"
        }
    }
</script>
