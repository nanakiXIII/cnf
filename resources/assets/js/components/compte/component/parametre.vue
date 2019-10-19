<template>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert" v-if="erreur != null">
                <ul class="list-group">
                    <li v-for="(e, key) in erreur"> {{e[0]}}</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Modifier mes informations</h5>
                    <p class="card-text">
                        <form v-on:submit.prevent="getSubmit">
                            <div class="form-group">
                                <label for="AdresseMail">Adresse Mail</label>
                                <input type="email" name="email" class="form-control" id="AdresseMail" v-model="user.data.email">
                            </div>
                            <div class="form-group">
                                <label for="mdp">Mot de passe</label>
                                <input type="password" class="form-control" name="password" id="mdp" v-model="user.data.password">
                            </div>
                            <div class="form-group">
                                <label for="mdpconfirm">Confirmation du mot de passe</label>
                                <input type="password" class="form-control"  name="password_confirmation" id="mdpconfirm" v-model="user.data.password_confirmation">
                            </div>
                            <div class="form-group">
                                Recevoir les notifications ?
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="oui" value="1" v-model="user.data.notification">
                                    <label class="form-check-label" for="oui">Oui</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="non" value="0" v-model="user.data.notification">
                                    <label class="form-check-label" for="non">Non</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Avatar">Avatar</label>
                                <input type="file" ref="file" class="form-control-file" id="Avatar" accept="image/*" @change="onFileChange">
                                <div class="progress mb-3" v-if="pourcentage != 0 && pourcentage != 'ok'">
                                    <div class="progress-bar bg-warning" role="progressbar" :style="'width:'+pourcentage +'%'" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-colorise btn-lg btn-block">Enregistrer</button>
                        </form>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Personnalisation</h5>
                    <p class="card-text">
                        <label class="my-1 mr-2" for="theme">Style du site</label>
                        <select class="custom-select custom-select mb-3" v-model="user.data.theme" id="theme" @change="getSubmit">
                            <option value="light">Clair</option>
                            <option value="dark">Sombre</option>
                        </select>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                theme: '',
                pourcentage:'',
                fichiers:{},
                file:'',
                erreur:null,
            }
        },

        watch:{
            theme(){
                if(this.theme != ''){
                    this.information('theme')
                }
            },
        },
        computed: {
            user(){
                return this.$store.getters.getProfile
            }
        },
        methods: {
            information: function (action) {
                this.$parent.theme = this.theme
                const { theme } = this;
                this.$store.dispatch('compteRequest', { theme, action })

            },
            getSubmit(){
                this.theme = this.user.data.theme;
                const data = this.user.data
                    axios.put('/api/user', data)
                        .then(response =>{
                            console.log(response);
                        })
                        .catch(errors =>{
                            this.erreur = errors.response.data.errors
                        })
            },
            onFileChange: function onFileChange(e) {
                e.preventDefault();
                this.file = this.$refs.file.files[0]
                this.fichiers.file = this.$refs.file.files[0].name
                let formData = new FormData();
                formData.append('file', this.file);
                axios.post('/api/user/avatar', formData, {
                    onUploadProgress: function( progressEvent ) {
                        this.pourcentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
                    }.bind(this)
                })
                    .then(response => {
                        this.pourcentage = 'ok';
                    })
            },

        },
        mounted(){

        }
    }
</script>