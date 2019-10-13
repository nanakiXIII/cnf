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
                                <vue-recaptcha
                                        :sitekey="key"
                                        :loadRecaptchaScript="true"
                                        ref="recaptcha"
                                        @verify="onVerify"
                                        @expired="onExpired"
                                >
                                </vue-recaptcha>

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
    import VueRecaptcha from 'vue-recaptcha';
    export default {
        components: { VueRecaptcha },
        data(){
            return {
                key:'6LdQM1YUAAAAABFGEJQYd9wju4lylDFW30GWKGWK',
                'email':'',
                'password':'',
                'remember':false,
                captcha : '',
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
            login: function () {
                const { email, password, remember, captcha } = this;
                this.$store.dispatch('authRequest', { email, password, remember, captcha })
                    .then(() => {
                        this.$router.push('/user/suivis')
                    })
            }
        },
        mounted(){
            this.$parent.titre = "Connexion"
        }
    }
</script>
