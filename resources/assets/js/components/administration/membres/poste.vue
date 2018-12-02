<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-plus"></i> Postes
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
                        <div class="row col-list">
                            <div class="col-md-4 t3">
                                <div class="col-head text-center">
                                    <span class="fas fa-video" aria-hidden="true"></span>
                                    <h2>Animes</h2>
                                </div>
                                <ul class="list-unstyled">
                                    <li v-for="m in membres.postes" @click="informations(m)" v-if="m.site == 'Animes'" class="text-center">
                                        <p class="option">
                                            {{ m.name }}
                                        </p>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 t3">
                                <div class="col-head text-center">
                                    <span class="fas fa-paint-brush" aria-hidden="true"></span>
                                    <h2>Scantrad</h2>
                                </div>
                                <ul class="list-unstyled">
                                    <li v-for="m in membres.postes" @click="informations(m)" v-if="m.site == 'Scantrad'" class="text-center">
                                        <p class="option">
                                            {{ m.name }}
                                        </p>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 t3">
                                <div class="col-head text-center">
                                    <span class="fas fa-book-open" aria-hidden="true"></span>
                                    <h2>Light Novel</h2>
                                </div>
                                <ul class="list-unstyled">
                                    <li v-for="m in membres.postes" @click="informations(m)" v-if="m.site == 'Light-novel'" class="text-center">
                                        <p class="option">
                                            {{ m.name }}
                                        </p>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 t3">
                                <div class="col-head text-center">
                                    <span class="fas fa-gamepad" aria-hidden="true"></span>
                                    <h2>Visual Novel</h2>
                                </div>
                                <ul class="list-unstyled">
                                    <li v-for="m in membres.postes" @click="informations(m)" v-if="m.site == 'Visual-novel'" class="text-center">
                                        <p class="option">
                                            {{ m.name }}
                                        </p>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 t3">
                                <div class="col-head text-center">
                                    <span class="fas fa-globe" aria-hidden="true"></span>
                                    <h2>Autres</h2>
                                </div>
                                <ul class="list-unstyled">
                                    <li v-for="m in membres.postes" @click="informations(m)" v-if="m.site == 'Autre'" class="text-center">
                                        <p class="option">
                                            {{ m.name }}
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-vue" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-if="modalAction == 'modifier' || modalAction == 'supprimer' && action != 'delete'">{{ information.site }}: {{ information.name }}</h4>
                        <h4 class="modal-title" v-if="modalAction == 'nouveau' && action != 'delete'">Nouveau poste</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body" v-if="modalAction == 'supprimer'">
                        <div class="col-md-12 text-center">
                            <div class="alert alert-danger" role="alert" v-if="erreurs">
                                Une erreur est survenue<br>
                                Vous ne pouvez pas supprimer un poste si il est lié a une personnes.
                            </div>
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

                    <div class="modal-body" v-if="action != 'delete' && modalAction != 'supprimer'">
                        <form v-on:change="formulaire" v-on:submit.prevent="formulaire" class="col-md-12">
                            <div class="form-group">
                                <label for="name">Nom</label>
                                <input type="text" class="form-control" id="name" v-model="information.name">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect2">Famille</label>
                                <select class="form-control" id="exampleFormControlSelect2" v-model="information.site">
                                    <option value="Animes">Animes</option>
                                    <option value="Scantrad">Scantrad</option>
                                    <option value="Light-novel">Light novel</option>
                                    <option value="Visual-novel">Visual novel</option>
                                    <option value="Autre">Autre</option>
                                </select>
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
                action:'postes',
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
                if (this.action == 'postes'){
                    this.informations(this.retour)
                    this.membres.postes.push(this.retour)
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
                this.action = "postesMod"
                $('#modal-vue').modal('show');
            },
            paginate(){
                const { data } = this
                this.$store.dispatch('MembresRequest', {data});
            },
            nouveau(){
                this.information = {}
                this.information.name = null
                this.information.site = "Animes"
                this.information.id = null
                this.modalAction = "nouveau"
                this.action = "postes"
                $('#modal-vue').modal('show');
            },
            condition(){
              this.modalAction = 'supprimer'
            },
            supprimer(etat){
                if(etat){
                    this.action = "delete"
                    this.formulaire()

                }else{
                 this.modalAction = 'modifier'
                }

            },
            formulaire() {
                const { action } = this;
                const { id, site, name} = this.information
                console.log(id, action)
                this.sauvegarde = true
                this.$store.dispatch('FormulaireRequest', { action, id, site, name })
                    .then(() => {
                    if (!this.erreurs){
                        if (action == 'delete'){
                            this.information.name = null
                            this.information.site = null
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
            this.$parent.titre = "Gestion des postes"
            this.paginate()
        }
    }
</script>