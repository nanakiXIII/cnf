<template>
    <div class="container">
        <div class="alert alert-danger mt-3" role="alert" v-if="authErrors.any()">
            <div v-text="authErrors.get('email')"></div>
            <div v-text="authErrors.get('password')"></div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="auth-form text-center">

                            <form method="POST" v-on:submit.prevent="resetPassword">
                                <div class="form-group row">
                                    <label for="email" class="sr-only">Adresse mail</label>
                                    <input type="email" id="email" class="form-control" placeholder="Adresse mail" required autofocus v-model="email">
                                    <input type="hidden" name="token" required v-model="token">
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="sr-only">Mot de passe</label>
                                    <input type="password" id="password" class="form-control" placeholder="Mot de passe" required v-model="password">
                                </div>
                                <div class="form-group row">
                                    <label for="password_confirmation" class="sr-only">Confirmation du mot de passe</label>
                                    <input type="password" id="password_confirmation" class="form-control" placeholder="Password Confirmation" required v-model="password_confirmation">
                                </div>
                                <button class="btn btn-outline-info btn-block mt-2 mb-2" type="submit">Nouveau Mot de passe</button>
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
                'action': 'password-reset',
                'email':'',
                'password':'',
                'password_confirmation':'',
                'token':''
            }
        },
        computed: {
            authErrors(){
                return this.$store.getters.authErrors;
            }
        },
        methods: {
            resetPassword: function () {
                const { action, email, password, password_confirmation, token } = this;
                this.$store.dispatch('authRequest', { action, email, password, password_confirmation, token })
                    .then(() => {
                        this.$router.push('/dashboard')
                    })
            }
        },
        mounted(){
            this.$parent.titre = "Nouveau mot de passe"
            let token = this.$route.params.token;
            if(!token){
                this.$router.push('/');
            }
            this.token = token;
        }
    }
</script>
