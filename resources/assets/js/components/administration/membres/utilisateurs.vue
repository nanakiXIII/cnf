<template>
    <div>
        <div class="container mt-5" v-if="membres != null && info == true">
            <div class="col-md-12">
                <table class="table text-center" v-show="information == null">
                    <thead>
                    <tr>
                        <th scope="col text-center">Avatar</th>
                        <th scope="col text-center">Pseudo</th>
                        <th scope="col text-center">E-mail</th>
                        <th scope="col text-center">Team</th>
                        <th scope="col text-center">Inscription</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="membre in membres.data" class="hover cursor" @click="information = membre">
                        <td><img :src="membre.avatar" alt="no-avatar" height="20px"></td>
                        <td>{{membre.name}}</td>
                        <td>{{membre.email}}</td>
                        <td v-if="membre.equipe == 1" class="text-success">Oui</td>
                        <td v-if="membre.equipe == 0" class="text-danger">Non</td>
                        <td>{{ membre.created_at.date | moment( 'Do MMMM YYYY') }}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="card p-2 bg-white col-md-12" v-if="information != null">
                    <div class="row">
                        <div class="col-md-2">
                            <img :src="information.avatar" alt="" width="100%">
                        </div>
                        <div class="col-md-10">
                            Pseudo : {{ information.name }} <br>
                            Email : {{ information.email }} <br>
                            Inscription : {{ information.created_at.date | moment( 'Do MMMM YYYY') }}
                        </div>
                    </div>
                    <form v-on:submit.prevent="formulaire">
                        <div class="col-md-12">
                            <h4 class="mt-3 mb-3 border-bottom">
                                Gestion des rôles
                            </h4>
                        </div>
                        <div class="col-md-12">
                            <li class="list-group-item col-md-6" v-for="r in membres.roles">
                                {{r.name}}
                                <label class="switch ">
                                    <input type="checkbox" class="success" :value="r.id" name="role[]" v-model="information.roleID">
                                    <span class="slider round"></span>
                                </label>
                            </li>
                        </div>
                        <div class="col-md-12">
                            <h4 class="mt-3 mb-3 border-bottom">
                                Gestion de la team
                            </h4>
                        </div>
                        <div class="col-md-12">
                            <li class="list-group-item col-md-6">
                                Membre du staff ?
                                <label class="switch ">
                                    <input type="checkbox" class="success" :value="true" id="team" v-model="information.equipe">
                                    <span class="slider round"></span>
                                </label>
                            </li>
                        </div>
                        <div class="col-md-12">
                            <h4 class="mt-3 mb-3 border-bottom">
                                Gestion des postes
                            </h4>
                        </div>
                        <div class="row">
                            <div class="col-md-6 border-right">
                                <h4 class="mt-3 mb-3 border-bottom text-center">
                                    Animes
                                </h4>
                                <li class="list-group-item col-md-12" v-for="p in membres.postes" v-if="p.site == 'Animes'">
                                    {{p.name}}
                                    <label class="switch ">
                                        <input type="checkbox" class="success" :value="p.id" name="poste[]" v-model="information.posteID">
                                        <span class="slider round"></span>
                                    </label>
                                </li>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mt-3 mb-3 border-bottom text-center">
                                    Scantrad
                                </h4>
                                <li class="list-group-item col-md-12" v-for="p in membres.postes" v-if="p.site == 'Scantrad'">
                                    {{p.name}}
                                    <label class="switch ">
                                        <input type="checkbox" class="success" :value="p.id" name="poste[]" v-model="information.posteID">
                                        <span class="slider round"></span>
                                    </label>
                                </li>
                            </div>
                            <div class="col-md-6 border-right">
                                <h4 class="mt-3 mb-3 border-bottom text-center">
                                    Light Novel
                                </h4>
                                <li class="list-group-item col-md-12" v-for="p in membres.postes" v-if="p.site == 'Light-novel'">
                                    {{p.name}}
                                    <label class="switch ">
                                        <input type="checkbox" class="success" :value="p.id" name="poste[]" v-model="information.posteID">
                                        <span class="slider round"></span>
                                    </label>
                                </li>
                            </div>
                            <div class="col-md-6">
                                <h4 class="mt-3 mb-3 border-bottom text-center">
                                    Visual Novel
                                </h4>
                                <li class="list-group-item col-md-12" v-for="p in membres.postes" v-if="p.site == 'Visual-novel'">
                                    {{p.name}}
                                    <label class="switch ">
                                        <input type="checkbox" class="success" :value="p.id" name="poste[]" v-model="information.posteID">
                                        <span class="slider round"></span>
                                    </label>
                                </li>
                            </div>
                            <div class="col-md-12">
                                <h4 class="mt-3 mb-3 border-bottom text-center">
                                    Autres
                                </h4>
                                <li class="list-group-item col-md-6" v-for="p in membres.postes" v-if="p.site == 'Autre'">
                                    {{p.name}}
                                    <label class="switch ">
                                        <input type="checkbox" class="success" :value="p.id" name="poste[]" v-model="information.posteID">
                                        <span class="slider round"></span>
                                    </label>
                                </li>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-danger btn-lg btn-block mt-3" @click="information = null">Annuler</button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-lg btn-block mt-3">Valider</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container" v-show="info == false">
            <div class="row mt-2">
                <div class="col-md-12 text-center">
                    <div class="spinner-border mt-5" style="width: 6rem; height: 6rem;" role="status">
                        <span class="sr-only">Chargement ...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
    var pluck = require('object.pluck');
    export default {
        data(){
            return {
                membres:null,
                info:false,
                information:null,
            }


        },
        computed: {
            user(){
                return this.$store.getters.getProfile
            }
        },
        methods: {
            formulaire() {
                this.info = false
                const data = this.information
                    axios.put('/api/administration/membres', data)
                        .then(response =>{
                            if(response.data.success == true){
                                this.information = null;
                                this.info = true
                            }else{
                                this.info = true
                            }
                        })
            },
            getInfo(){
                axios.get('/api/administration/membres')
                    .then(response => {
                        this.membres = response.data
                        this.info = true
                        this.$parent.titre = "Gestion des utilisateurs"
                    })

            },

        },
        mounted(){
            this.$parent.titre = "Chargements ..."
            this.getInfo()
        }
    }
</script>