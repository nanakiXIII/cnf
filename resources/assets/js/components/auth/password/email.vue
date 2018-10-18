<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="auth-form text-center">
                            <form method="POST" v-on:submit.prevent="sendPasswordResetEmail" v-if="seconds>4">

                                <label for="email" class="sr-only">Adresse mail</label>
                                <input type="email" id="email" class="form-control" placeholder="Adresse mail" required autofocus v-model="email">

                                <button class="btn btn-outline-info btn-block mt-2 mb-2" type="submit" :disabled="disableSubmit">Valider</button>
                                 <router-link :to="{ name: 'login' }">Connexion</router-link>
                            </form>
                            <div class="container" v-else>
                                <h2 class="text-success mt-5">Nouveau mot de passe envoyé par mail</h2>

                                <div class="progress mt-3 mb-3 ml-5 mr-5">
                                    <div class="progress-bar bg-success" role="progressbar" :style="progress" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <h3>Redirection dans {{seconds}} secondes</h3>
                            </div>

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
                'disableSubmit':false,
                'email':'',
                'seconds': 5
            }
        },
        computed:{
            progress:function(){
                return 'width: ' + ( 20*Math.abs(5-this.seconds) ) + '%';
            }
        },
        methods: {
            sendPasswordResetEmail: function () {
                self = this;
                self.disableSubmit=true;
                axios.post('/password/email', {'email':self.email} )
                    .then((resp) => {
                        self.countdownRedirect();
                    })
                    .catch(()=>{
                        self.disableSubmit=false;
                        self.seconds=5;
                    })
            },
            countdownRedirect: function () {
                self = this;
                setInterval(()=>{
                    self.seconds=self.seconds-1;
                    if (self.seconds === 0) {
                        self.$router.push('/login');
                    }
                }, 1000);

            },
        },
        mounted(){
            this.$parent.titre = "Mot de passe oublié"
        }
    }
</script>
