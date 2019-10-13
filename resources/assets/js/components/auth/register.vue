<template>
    <div class="container">
        <div class="alert alert-danger mt-3" role="alert" v-if="authErrors.has('name') || authErrors.has('email') || authErrors.has('password')">
            <div v-text="authErrors.get('name')"></div>
            <div v-text="authErrors.get('email')"></div>
            <div v-text="authErrors.get('password')"></div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="auth-form text-center">
                            <form method="POST" v-on:submit.prevent="register" @keydown="authErrors.clear($event.target.name)">
                                <router-link :to="{ name: 'login' }">

                                </router-link>
                                <div class="form-group row">
                                    <label for="name" class="sr-only">Pseudo</label>
                                    <input type="text" id="name" class="form-control" placeholder="Pseudo" required autofocus v-model="name">
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="sr-only">Adresse mail</label>
                                    <input type="email" id="email" class="form-control" placeholder="Adresse mail" required v-model="email">
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="sr-only">Mot de passe</label>
                                    <input type="password" id="password" class="form-control" placeholder="Mot de passe" required v-model="password">
                                </div>
                                <div class="form-group row">
                                    <label for="password_confirmation" class="sr-only">Confirmation du mot de passe</label>
                                    <input type="password" id="password_confirmation" class="form-control" placeholder="Confirmation du mot de passe" required v-model="password_confirmation">
                                </div>
                                <vue-recaptcha
                                        :sitekey="key"
                                        :loadRecaptchaScript="true"
                                        ref="recaptcha"
                                        @verify="onVerify"
                                        @expired="onExpired"
                                >
                                </vue-recaptcha>

                                <button class="btn btn-outline-success btn-block mt-2 mb-2" type="submit">Créer un Compte</button>

                                Déja inscrit ? <router-link :to="{ name: 'login' }">Connexion</router-link>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>


<script>
    import VueRecaptcha from 'vue-recaptcha';
    export default {
        components: { VueRecaptcha },
        data(){
            return {
                key:'6LdQM1YUAAAAABFGEJQYd9wju4lylDFW30GWKGWK',
                captcha : '',
                'action':'register',
                'name':'',
                'email':'',
                'password':'',
                'password_confirmation':'',
            }
        },
        computed: {
            authErrors(){
                return this.$store.getters.authErrors;
            }
        },
        methods: {
            onVerify: function (response) {
                this.captcha = true
            },
            onExpired: function () {
                this.captcha = ''
            },
            register: function () {
                const { action, name, email, password, password_confirmation, captcha } = this;
                this.$store.dispatch('authRequest', { action, name, email, password, password_confirmation, captcha })
                    .then(() => {
                        this.$router.push('/user/suivis')
                    })
            }
        },
        mounted(){
            this.$parent.titre = "Création d'un compte"
        }
    }
</script>
