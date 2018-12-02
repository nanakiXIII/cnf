<template>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="mb-0">
                                    <i class="fas fa-folder-plus"></i> Ajouter une série
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        {{ someData }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    let cheerio = require('cheerio');
    export default {
        data(){
            return {
                data:"liste",
                information:false,
                action:'postes',
                user_id:'',
                modalAction:'',
                sauvegarde:false,
                erreurs: false,
                someData: null
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
            this.$parent.titre = "Ajouter une série"

                fetch('/',{
                    headers:{'Content-Type': 'text/html'},
                    credentials: 'include',
                    mode: 'no-cors',
                }).then(function(response) {
                    console.log(response)
                    return response.text()

                }).then(function(body) {
                    console.log(body)
                })

            this.paginate()
        }
    }
</script>