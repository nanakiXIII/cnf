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
                            <li class="list-group-item" v-for="m in membres.data" @click="informations(m)">
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
                                        {{ information.postes }}
                                    </li>
                                    <li class="list-group-item"><i class="fas fa-grin-tears icon"></i>
                                        {{ information.team }}
                                    </li>
                                    <li class="list-group-item"><i class="fas fa-calendar icon"></i> {{ information.created_at }}</li>
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
                                <h3 class="titre mb-3">
                                    Modifier les rôles
                                </h3>
                                <form action="" class="row">
                                    <template v-for="r in membres.roles">
                                        <div class="col-md-3">
                                            <label :for="r.id" class="btn btn-info mr-2">{{ r.name }}
                                                <input type="checkbox" :value="r.id" :id="r.id" class="badgebox" v-model="checkbox">
                                                <span class="badge">&check;</span>
                                            </label>
                                        </div>

                                    </template>
                                    <button type="button" class="btn btn-success btn-lg btn-block mt-3">Valider</button>
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
    export default {
        data(){
            return {
               data:"liste",
                information:false,
                modification:false,
                checkbox:[],
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
            news(){

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
            informations(info){
                this.modification = false;
                this.information = info
                this.checkbox = this.information.roleID
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
        },
        mounted(){
            this.$parent.titre = "Gestion des utilisateurs"
            this.paginate()

        }
    }
</script>