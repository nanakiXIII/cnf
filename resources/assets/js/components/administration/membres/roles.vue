<template>
    <div>
        <div class="container mt-5" v-if="info == true">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-danger" role="alert" v-if="erreur != null">
                                <ul class="list-group">
                                    <li v-for="e in erreur"> {{e}}</li>
                                </ul>
                            </div>
                            <form v-on:submit.prevent="formulaire" class="col-md-12 mb-4" v-if="visible == true">
                                <div class="form-group">
                                    <label for="name">Nom</label>
                                    <input type="text" class="form-control" id="name" v-model="information.name" autofocus>
                                </div>
                                <div class="col-md-12">
                                    <h4 class="mt-3 mb-3 border-bottom text-center">
                                        Permissions
                                    </h4>
                                    <div class="row">
                                        <li class="list-group-item col-md-6" v-for="p in grades.permissions">
                                            {{p.name}}
                                            <label class="switch ">
                                                <input type="checkbox" class="success" :value="p.id" name="permissions[]" v-model="information.permissionID">
                                                <span class="slider round"></span>
                                            </label>
                                        </li>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-warning btn-lg btn-block mt-3" @click="visible = false; information={permissionID:[]}">Annuler</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-danger btn-lg btn-block mt-3" v-if="information.id" @click="supprimer(information)">Supprimer</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-success btn-lg btn-block mt-3">Valider</button>
                                    </div>
                                </div>
                            </form>
                            <div class="row" v-if="visible == false">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-center" scope="col">Nom</th>
                                        <th class="text-center" scope="col">Permissions
                                            <div class="pull-right">
                                                <li class="text-success fas fa-plus cursor" v-if="visible == false" @click="visible = true;information = {permissionID:[]}; erreur=null "> Nouveau </li>
                                            </div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="hover cursor" v-for="(m, index) in grades.data" @click="information = m; visible = true" >
                                        <td class="text-center">
                                            {{ m.name }}
                                        </td>
                                        <td class="text-center">
                                            <i v-for="p in m.permission" v-if="m.permission">
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
                grades:null,
                info:false,
                visible:false,
                information:{permissionID:[]},
                erreur:null,
            }


        },
        computed: {
            user(){
                return this.$store.getters.getProfile
            }
        },
        watch:{

        },
        methods: {
            getInfo(){
                axios.get('/api/administration/grades')
                    .then(response => {
                        this.grades = response.data
                        this.info = true
                        this.$parent.titre = "Gestion des grades"
                    })

            },
            formulaire() {
                this.erreur = null
                this.info = false
                const data = this.information
                if(data.id){
                    axios.put('/api/administration/grades', data)
                        .then(response =>{
                            if(response.data.success == true){
                                this.information = {permissionID:[]};
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
                }else{
                    axios.post('/api/administration/grades', data)
                        .then(response =>{
                            if(response.data.success == true){
                                this.information = {permissionID:[]};
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
                        axios.delete('/api/administration/grades/'+info.id)
                            .then(response =>{
                                if(response.data.success == true){
                                    this.getInfo()
                                    this.information = {permissionID:[]};
                                    this.visible = false;
                                }else{
                                    this.info = true
                                }
                            }).catch(error => {
                            this.info = true;
                            this.erreur = ['Vous ne pouvez pas supprimer ce grade, car il est actuellement utilisé']
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