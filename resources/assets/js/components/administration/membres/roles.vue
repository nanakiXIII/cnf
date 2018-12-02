<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-shield"></i> Rôles
                                </h5>
                            </div>
                            <div class="col-md-2 text-right">
                                <h5 class="mb-0" @click="nouveau">
                                    <i class="fas fa-plus text-success"></i>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Nom</th>
                                        <th class="text-center" scope="col">Permission</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(m, index) in membres.roles" @click="informations(m, index)" >
                                        <td class="text-center">
                                            {{ m.name }}
                                        </td>
                                        <td class="text-center">
                                            <i v-for="p in m.permissions" v-if="m.permissions">
                                                {{ p }}
                                            </i>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-vue" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-if="modalAction == 'modifier' || modalAction == 'supprimer' && action != 'rolesDelete'">Modification: {{ information.name }}</h4>
                        <h4 class="modal-title" v-if="modalAction == 'nouveau' && action != 'rolesDelete'">Nouveau rôle</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body" v-if="modalAction == 'supprimer'">
                        <div class="col-md-12 text-center">
                            Etes vous sur de supprimer
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-outline-danger" @click="supprimer(true)">
                                    <i class="fas fa-trash"></i> Oui
                                </button>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="button" class="btn btn-outline-success" @click="supprimer(false)">
                                    Hum ... Non
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-body" v-if="action != 'rolesDelete' && modalAction != 'supprimer'">
                        <div class="alert alert-danger" role="alert" v-if="erreurs">
                            Une erreur est survenue<br>
                            "{{ erreur }}"
                        </div>
                        <form v-on:change="formulaire" v-on:submit.prevent="" class="col-md-12">
                            <div class="form-group">
                                <label for="name">Nom</label>
                                <input type="text" class="form-control" id="name" v-model="information.name">
                            </div>
                            <div>
                                <template v-for="p in membres.perm" v-if="modalAction != 'nouveau'">
                                    <label :for="p.id+'perm'"
                                           v-if="information.permissionsID"
                                           v-bind:class="[found(information.permissionsID, p.id) ? 'btn-success' : 'btn-warning']"
                                           class="btn col-md-5 mr-2 ml-2">
                                        {{ p.name }}
                                        <input type="checkbox" :value="p.id"
                                               :id="p.id+'perm'"
                                               class="badgebox"
                                               v-model="information.permissionsID">
                                        <span class="badge">&check;</span>
                                    </label>

                                </template>
                            </div>
                        </form>
                    </div>
                    <!-- Modal Actions -->
                    <div class="modal-footer" >
                        <div class="col-md-6">
                            <button type="button" class="btn btn-outline-danger" @click="condition" v-if="modalAction == 'modifier'">
                                <i class="fas fa-trash"></i>
                                Supprimer
                            </button>
                        </div>
                        <div class="col-md-6 text-right">
                            <span class="btn btn-outline-success" v-if="sauvegarde">
                                Sauvegarde en cours
                                <i class="fas fa-sync-alt rotation"></i>
                            </span>
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
                action:'Aroles',
                user_id:'',
                modalAction:'',
                sauvegarde:false,
                erreurs: false,
                index : ''
            }


        },
        computed: {
            retour(){
                return this.$store.getters.getReponse
            },

            membres(){
                return this.$store.getters.getMembres;
            },
            user(){
                return this.$store.getters.getProfile
            },
            erreur(){
                return this.$store.getters.getErrorM
            }
        },
        watch:{
            retour(){
                if (this.action == 'Aroles'){
                    this.informations(this.retour)
                    this.membres.roles.push(this.retour)
                }
                if (this.action == 'rolesMod'){
                    this.information.permissions = this.retour.permissions
                }
            },
            erreur(){
                this.erreurs = this.erreur
            },
            information(){
                this.erreurs = false
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
            informations(info, index){
                this.sauvegarde = false
                this.information = info
                this.index = index
                this.modalAction = "modifier"
                this.erreurs = ""
                this.action = "rolesMod"
                $('#modal-vue').modal('show');
            },
            paginate(){
                const { data } = this
                this.$store.dispatch('MembresRequest', {data});
            },
            nouveau(){
                this.information = {}
                this.information.name = null
                this.information.id = null
                this.information.permissionsID = []
                this.modalAction = "nouveau"
                this.action = "Aroles"
                $('#modal-vue').modal('show');
            },
            condition(){
                this.modalAction = 'supprimer'
            },
            supprimer(etat){
                if(etat){
                    this.action = "rolesDelete"
                    this.formulaire()

                }else{
                    this.modalAction = 'modifier'
                }

            },
            formulaire() {
                const { action } = this;
                const { id, site, name, permissionsID} = this.information
                this.erreurs = ""
                this.sauvegarde = true
                this.$store.dispatch('FormulaireRequest', { action, id, site, name, permissionsID })
                    .then(() => {
                        if (!this.erreurs){
                            if (action == 'rolesDelete'){
                                this.information.name = null
                                this.information.permissions = null
                                this.information.id = null
                                this.membres.roles.splice(this.index, 1)
                                $('#modal-vue').modal('hide');
                            }
                        }else{
                            this.action = "modifier"
                        }
                        setTimeout(function () { this.sauvegarde = false }.bind(this), 1000)
                    })
                    .catch(()=>{
                        console.log('erreur')
                    })
            }
        },
        mounted(){
            this.$parent.titre = "Gestion des rôles"
            this.paginate()
        }
    }
</script>