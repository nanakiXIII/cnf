<template>
    <div class="col-md-12 mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-users"></i> Membres
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item" v-for="(m, key) in membres.data" @click="informations(m, key)">
                                <i class="fas fa-grin-tears icon" v-if="m.equipe"></i>
                                <i class="fas fa-user icon" v-if="!m.equipe"></i>
                                {{ m.name }}

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card" v-if="information">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="mb-0">
                                    <i class="fas fa-user"></i> {{ information.name }}
                                </h5>
                            </div>
                            <div class="col-md-4 text-right">
                                <h5 class="mb-0 inline"><i class="fas fa-cogs" @click="modif()"></i></h5>
                                <h5 class="mb-0 inline"><i class="fas fa-times colorise " @click="information = false"></i></h5>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row" v-if="!modification">
                            <div class="col-md-2">
                                <img class="align-middle" :src="information.avatar" alt="" v-if="information.avatar != 'noAvatar'" width="100%">
                            </div>
                            <div class="col-md-10">
                                <ul class="list-group">
                                    <li class="list-group-item"><i class="fas fa-envelope icon"></i> {{ information.email }}</li>
                                    <li class="list-group-item" v-if="information.equipe">
                                        <i class="fas fa-coffee icon"></i>
                                        <span class="badge badge-secondary mr-2" v-for="p in information.poste">
                                            <i class="fas fa-video" v-if="p.site == 'Animes'"></i>
                                            <i class="fas fa-book-open" v-if="p.site == 'Light-novel'"></i>
                                            <i class="fas fa-paint-brush" v-if="p.site == 'Scantrad'"></i>
                                            <i class="fas fa-gamepad" v-if="p.site == 'Visual-novel'"></i>
                                            <i class="fas fa-globe" v-if="p.site == 'autre'"></i>
                                            {{ p.name }}
                                        </span>
                                    </li>
                                    <li class="list-group-item"><i class="fas fa-grin-tears icon"></i>
                                        {{ information.team }}
                                    </li>
                                    <li class="list-group-item"><i class="fas fa-calendar icon"></i> {{ information.created_at | moment( 'Do MMMM YYYY') }}</li>
                                    <li class="list-group-item"><i class="fas fa-user-shield icon"></i>
                                        <span class="badge badge-secondary mr-2" v-for="role in information.role" v-if="information.role">
                                            {{ role }}
                                        </span>
                                    </li>
                                    <li class="list-group-item"><i class="fas fa-user-secret icon"></i>
                                        <span class="badge badge-secondary mr-2" v-for="perm in information.permission">
                                            {{ perm }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row" v-else>
                            <div class="col-md-12">

                                <form v-on:submit.prevent="formulaire" class="row">
                                    <div class="col-md-12">
                                        <h3 class="titre mb-3 border-bottom">
                                            Gestion des rôles
                                        </h3>
                                    </div>
                                    <template v-for="r in membres.roles">
                                        <div class="col-lg-3 col-md-6 col-xs-6">
                                            <label :for="r.id" v-bind:class="[found(information.roleID, r.id) ? 'btn-success' : 'btn-warning']" class="btn mr-2">{{ r.name }}
                                                <input type="checkbox" :value="r.id" :id="r.id" class="badgebox" v-model="information.roleID">
                                                <span class="badge">&check;</span>
                                            </label>
                                        </div>
                                    </template>
                                    <div class="col-md-12">
                                        <h3 class="titre mb-3 border-bottom">
                                            Gestion de la team
                                        </h3>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="team" v-bind:class="[information.equipe ? 'btn-success' : 'btn-danger']" class="btn  mr-2"> Fait parti de la team ?
                                            <input type="checkbox" value="true" id="team" class="badgebox" v-model="information.equipe">
                                            <span class="badge">&check;</span>
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <h3 class="titre mb-3 border-bottom">
                                            Gestion des postes
                                        </h3>
                                    </div>
                                    <div class="col-md-4 border-right">
                                        <h3 class="titre mb-3 border-bottom text-center">
                                            Animes
                                        </h3>
                                        <template v-for="p in membres.postes">
                                            <label :for="p.id+'poste'" v-bind:class="[found(information.posteID, p.id) ? 'btn-success' : 'btn-warning']" class="btn  mr-2" v-if="p.site == 'Animes'"> {{ p.name }}
                                                <input type="checkbox" :value="p.id" :id="p.id+'poste'" class="badgebox" v-model="information.posteID">
                                                <span class="badge">&check;</span>
                                            </label>
                                        </template>
                                    </div>
                                    <div class="col-md-4 border-right">
                                        <h3 class="titre mb-3 border-bottom text-center">
                                            Scans
                                        </h3>
                                        <template v-for="p in membres.postes">
                                            <label :for="p.id+'poste'" v-bind:class="[found(information.posteID, p.id) ? 'btn-success' : 'btn-warning']" class="btn mr-2" v-if="p.site == 'Scantrad'"> {{ p.name }}
                                                <input type="checkbox" :value="p.id" :id="p.id+'poste'" class="badgebox" v-model="information.posteID">
                                                <span class="badge">&check;</span>
                                            </label>
                                        </template>
                                    </div>
                                    <div class="col-md-4">
                                        <h3 class="titre mb-3 border-bottom text-center">
                                            LN
                                        </h3>
                                        <template v-for="p in membres.postes">
                                            <label :for="p.id+'poste'" v-bind:class="[found(information.posteID, p.id) ? 'btn-success' : 'btn-warning']" class="btn mr-2" v-if="p.site == 'Light-novel'"> {{ p.name }}
                                                <input type="checkbox" :value="p.id" :id="p.id+'poste'" class="badgebox" v-model="information.posteID">
                                                <span class="badge">&check;</span>
                                            </label>
                                        </template>
                                    </div>
                                    <div class="col-md-4 border-right">
                                        <h3 class="titre mb-3 border-bottom text-center">
                                           VN
                                        </h3>
                                        <template v-for="p in membres.postes">
                                            <label :for="p.id+'poste'" v-bind:class="[found(information.posteID, p.id) ? 'btn-success' : 'btn-warning']" class="btn mr-2" v-if="p.site == 'Visual-novel'"> {{ p.name }}
                                                <input type="checkbox" :value="p.id" :id="p.id+'poste'" class="badgebox" v-model="information.posteID">
                                                <span class="badge">&check;</span>
                                            </label>
                                        </template>
                                    </div>
                                    <div class="col-md-4 border-right">
                                        <h3 class="titre mb-3 border-bottom text-center">
                                            Autres
                                        </h3>
                                        <template v-for="p in membres.postes">
                                            <label :for="p.id+'poste'" v-bind:class="[found(information.posteID, p.id) ? 'btn-success' : 'btn-warning']" class="btn mr-2" v-if="p.site == 'Autre'"> {{ p.name }}
                                                <input type="checkbox" :value="p.id" :id="p.id+'poste'" class="badgebox" v-model="information.posteID">
                                                <span class="badge">&check;</span>
                                            </label>
                                        </template>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-lg btn-block mt-3">Valider</button>
                                </form>
                            </div>
                        </div>
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
               data:"liste",
                information:false,
                modification:false,
                action:'roles',
                cle:false,

            }


        },
        computed: {
            membres(){
                return this.$store.getters.getMembres;
            },
            user(){
                return this.$store.getters.getProfile
            }
        },
        watch:{
            membres(){
                if(this.cle){
                    this.informations(this.membres.data[this.cle], this.cle)
                    this.information = this.membres.data[this.cle]
                }
            }
        },
        methods: {
            found: function (tab, element) {
                let response = tab.indexOf(element)
                if (response >= 0){
                    return true
                }
                else{
                    return false
                }
            },
            informations(info, key){
                this.cle = key
                this.modification = false;
                this.information = info


            },
            paginate(){
                const { data } = this
                this.$store.dispatch('MembresRequest', {data});
            },
            modif(){
                if(this.modification){
                    this.modification = false
                }else{
                    this.modification = true
                }
            },
            formulaire: function () {
                const { action } = this;
                const { posteID, id, equipe , roleID } = this.information;
                this.$store.dispatch('FormulaireRequest', { action, posteID, id, equipe, roleID })
                    .then(() => {
                        setTimeout(function () { this.paginate() }.bind(this), 1000)
                        this.modification = false;

                    })
            }
        },
        mounted(){
            this.$parent.titre = "Gestion des utilisateurs"
            this.paginate()
        }
    }
</script>