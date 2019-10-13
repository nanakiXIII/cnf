<template>
    <div>
        <div class="container mt-5" v-if="info == true">
            <div class="row">
                <div class="table-responsive">
                    <form v-on:submit.prevent="getSubmitNew()">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Crée une permission" name="name" v-model="nouveau" aria-describedby="nouvellePermission">
                            <div class="input-group-append">
                                <button class="btn btn-outline-success" type="submit" id="nouvellePermission">Valider</button>
                            </div>
                        </div>
                    </form>
                    <table class="table">
                        <tbody>
                        <tr v-for="(info, key) in information" v-if="information != null">
                            <td>
                                <template v-if="info.created_at != true">
                                    {{info.name}}
                                </template>

                                <form v-if="info.created_at == true" v-on:submit.prevent="getSubmitModifier(info, key)">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="name" v-model="info.name" aria-describedby="genre">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success" type="submit" id="genre">Valider</button>
                                        </div>
                                    </div>
                                </form>

                            </td>
                            <td class="text-right">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-outline-info" @click="getModifier(info)" v-if="info.created_at != true">Modifier</button>
                                    <button type="button" class="btn btn-outline-warning" @click="getModifier(info)" v-if="info.created_at == true">Annuler</button>
                                    <button type="button" class="btn btn-outline-danger" @click="getDestroy(info)">Supprimer</button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
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
    export default {
        data(){
            return {
                nouveau:"",
                information:null,
                info:false
            }
        },
        methods: {
            doNothing(){},
            getInfo(){
                axios.get('/api/administration/permissions')
                    .then(response => {
                        this.information = response.data
                        this.info = true
                    })
            },
            getModifier(info){
                if(info.created_at == true){
                    info.created_at = false
                }else{
                    info.created_at = true
                }
            },
            getSubmitModifier(info, key){
                axios.put('/api/administration/permissions', info)
                    .then(response =>{
                        if(response.data.success == true){
                            this.getInfo()
                        }else{

                        }
                    })
            },
            getSubmitNew(){
                const name = this.nouveau
                if (this.nouveau != ""){
                    axios.post('/api/administration/permissions', {name})
                        .then(response =>{
                            if(response.data.success == true){
                                this.nouveau = null
                                this.getInfo()
                            }else{

                            }
                        })
                }
            },
            getDestroy(info){
                let options = {
                    okText: 'Confirmer',
                    cancelText: 'Fermer',
                    type:'hard',
                    verification: 'Supprimer',
                    verificationHelp: 'Tapez [+:verification] avant de confirmer',
                    message: 'Etes-vous sur de vouloir supprimer '+info.name,
                    clicksCount: 1,
                    backdropClose: true,
                };
                this.$dialog.confirm(options.message,options)
                    .then(() => {
                        axios.delete('/api/administration/permissions/'+info.id)
                            .then(response =>{
                                if(response.data.success == true){
                                    this.getInfo()
                                }else{

                                }
                            })
                    })
            }

        },

        mounted(){
            this.$parent.titre = "Gestion des genres"
            this.getInfo()
        }
    }
</script>