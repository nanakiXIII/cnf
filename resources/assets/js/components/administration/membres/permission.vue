<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-shield"></i> Permissions
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
                            <ul class="list-group col-lg-12">
                                <li class="list-group-item cursor" v-for="m in membres.perm" @click="informations(m)">
                                    {{ m.name }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-vue" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-if="modalAction == 'modifier' || modalAction == 'supprimer' && action != 'permissionDelete'">Modification: {{ information.name }}</h4>
                        <h4 class="modal-title" v-if="modalAction == 'nouveau' && action != 'permissionDelete'">Nouvelle permission</h4>
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

                    <div class="modal-body" v-if="action != 'permissionDelete' && modalAction != 'supprimer'">
                        <div class="alert alert-danger" role="alert" v-if="erreurs">
                            Une erreur est survenue<br>
                            "{{ erreur }}"
                        </div>
                        <form v-on:change="formulaire" v-on:submit.prevent="formulaire" class="col-md-12">
                            <div class="form-group">
                                <label for="name">Nom</label>
                                <input type="text" class="form-control" id="name" v-model="information.name">
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
                action:'permission',
                user_id:'',
                modalAction:'',
                sauvegarde:false,
                erreurs: false
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
                if (this.action == 'permission'){
                    this.informations(this.retour)
                    this.membres.perm.push(this.retour)
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
            informations(info){
                this.sauvegarde = false
                this.information = info
                this.modalAction = "modifier"
                this.erreurs = ""
                this.action = "permissionMod"
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
                this.modalAction = "nouveau"
                this.action = "permission"
                $('#modal-vue').modal('show');
            },
            condition(){
                this.modalAction = 'supprimer'
            },
            supprimer(etat){
                if(etat){
                    this.action = "permissionDelete"
                    this.formulaire()

                }else{
                    this.modalAction = 'modifier'
                }

            },
            formulaire() {
                const { action } = this;
                const { id, site, name} = this.information
                this.erreurs = ""
                this.sauvegarde = true
                this.$store.dispatch('FormulaireRequest', { action, id, site, name })
                    .then(() => {
                        if (!this.erreurs){
                            if (action == 'permissionDelete'){
                                this.information.name = null
                                this.information.id = null
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
            this.$parent.titre = "Gestion des permissions"
            this.paginate()
        }
    }
</script>