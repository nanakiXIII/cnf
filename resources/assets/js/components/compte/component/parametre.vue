<template>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Modifier mes informations</h5>
                    <p class="card-text">
                        <form>
                            <div class="form-group">
                                <label for="AdresseMail">Adresse Mail</label>
                                <input type="text" class="form-control" id="AdresseMail" v-model="user.data.email">
                            </div>
                            <div class="form-group">
                                <label for="mdp">Mot de passe</label>
                                <input type="password" class="form-control" id="mdp">
                            </div>
                            <div class="form-group">
                                <label for="mdpconfirm">Confirmation du mot de passe</label>
                                <input type="password" class="form-control" id="mdpconfirm">
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
                                <input type="file" class="form-control-file" id="Avatar" accept="image/*">
                            </div>
                            <button type="button" class="btn btn-outline-colorise btn-lg btn-block">Enregistrer</button>
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
                        <select class="custom-select custom-select mb-3" v-model="theme" id="theme">
                            <option value="" selected>Selectionnez un theme</option>
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
                theme: ''
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

        },
        mounted(){

        }
    }
</script>