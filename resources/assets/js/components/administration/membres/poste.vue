<template>
    <div>
        <div class="container mt-5" v-if="info == true">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        <li class="text-success fas fa-plus cursor" v-if="visible == false" @click="visible = true;information = {site:'Animes'}; erreur=null "> Nouveau </li>
                                    </div>
                                    <div class="alert alert-danger" role="alert" v-if="erreur != null">
                                        <ul class="list-group">
                                            <li v-for="e in erreur"> {{e}}</li>
                                        </ul>
                                    </div>
                                    <form v-on:submit.prevent="formulaire" class="col-md-12 mb-4" v-show="visible == true">
                                        <div class="form-group">
                                            <label for="name">Nom</label>
                                            <input type="text" class="form-control" id="name" v-model="information.name" autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect2">Famille</label>
                                            <select class="form-control" id="exampleFormControlSelect2" v-model="information.site">
                                                <option value="Animes" selected>Animes</option>
                                                <option value="Scantrad">Scantrad</option>
                                                <option value="Light-novel">Light novel</option>
                                                <option value="Visual-novel">Visual novel</option>
                                                <option value="Autre">Autre</option>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-warning btn-lg btn-block mt-3" @click="visible = false">Annuler</button>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-danger btn-lg btn-block mt-3" v-if="information.id" @click="supprimer(information)">Supprimer</button>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-success btn-lg btn-block mt-3">Valider</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-6 border-right">
                                    <h4 class="mt-3 mb-3 border-bottom text-center">
                                        Animes
                                    </h4>
                                    <li class="list-group-item col-md-12" v-for="p in postes" v-if="p.site == 'Animes'" @click="information = p; visible = true;">
                                        {{p.name}}
                                    </li>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="mt-3 mb-3 border-bottom text-center">
                                        Scantrad
                                    </h4>
                                    <li class="list-group-item col-md-12" v-for="p in postes" v-if="p.site == 'Scantrad'" @click="information = p; visible = true;">
                                        {{p.name}}
                                    </li>
                                </div>
                                <div class="col-md-6 border-right">
                                    <h4 class="mt-3 mb-3 border-bottom text-center">
                                        Light Novel
                                    </h4>
                                    <li class="list-group-item col-md-12" v-for="p in postes" v-if="p.site == 'Light-novel'" @click="information = p; visible = true;">
                                        {{p.name}}
                                    </li>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="mt-3 mb-3 border-bottom text-center">
                                        Visual Novel
                                    </h4>
                                    <li class="list-group-item col-md-12" v-for="p in postes" v-if="p.site == 'Visual-novel'" @click="information = p; visible = true;">
                                        {{p.name}}
                                    </li>
                                </div>
                                <div class="col-md-12">
                                    <h4 class="mt-3 mb-3 border-bottom text-center">
                                        Autres
                                    </h4>
                                    <li class="list-group-item col-md-6" v-for="p in postes" v-if="p.site == 'Autre'" @click="information = p; visible = true;">
                                        {{p.name}}
                                    </li>
                                </div>
                            </div>
                        </div>
                    </div>
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
                postes:null,
                info:false,
                information:{site:'Animes'},
                visible:false,
                erreur:null,
            }


        },
        computed: {
            user(){
                return this.$store.getters.getProfile
            }
        },
        methods: {
            getInfo(){
                axios.get('/api/administration/postes')
                    .then(response => {
                        this.postes = response.data
                        this.info = true
                        this.$parent.titre = "Gestion des postes"
                    })

            },
            formulaire() {
                this.erreur = null
                this.info = false
                const data = this.information
                if(data.id){
                    axios.put('/api/administration/postes', data)
                        .then(response =>{
                            if(response.data.success == true){
                                this.information = {site:'Animes'};
                                this.info = true
                                this.visible = false;
                            }else{
                                this.info = true
                            }
                        })
                        .catch(errors =>{
                            this.erreur = errors.response.data.errors.name
                            this.info = true;
                        })
                }else{
                    axios.post('/api/administration/postes', data)
                        .then(response =>{
                            if(response.data.success == true){
                                this.information = {site:'Animes'};
                                this.visible = false;
                                this.getInfo();
                            }else{
                                this.info = true
                            }
                        })
                        .catch(errors =>{
                            this.erreur = errors.response.data.errors.name
                            this.info = true;
                        })
                }

            },
            supprimer(info){
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
                    this.info = false;
                    this.erreur = null;
                        axios.delete('/api/administration/postes/'+info.id)
                            .then(response =>{
                                if(response.data.success == true){
                                    this.getInfo()
                                    this.information = {site:'Animes'};
                                    this.visible = false;
                                }else{
                                    this.info = true
                                }
                            }).catch(error => {
                                this.info = true;
                                this.erreur = ['Vous ne pouvez pas supprimer ce poste, car il est actuellement utilisé']
                            })
                    })
            }
        },
        mounted(){
            this.$parent.titre = "Chargement ..."
            this.getInfo()
        }
    }
</script>