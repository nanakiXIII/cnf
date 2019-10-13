<template>
    <div class="container mt-5">
        <div class="row">
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col text-center">Pseudo</th>
                    <th scope="col text-center">Commentaire</th>
                    <th scope="col text-center">News</th>
                    <th scope="col text-center">Date Publication</th>
                    <th scope="col text-center">Options</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="n in news">
                    <td v-text="n.user.name"></td>
                    <td v-text="n.comment"></td>
                    <td v-text="n.news.titre"></td>
                    <td class="text-center">
                            {{n.created_at.date | moment("dddd Do MMMM YYYY") }}
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-secondary" @click="getDestroy(n)">Supprimer</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>
<script>
    export default {
        data(){
            return {
                news:{},
            }


        },
        computed: {

        },
        watch:{

        },
        methods: {
            getInfo(){
                axios.get('/api/administration/commentaires')
                    .then(response => {
                        this.news = response.data.data
                        this.$parent.titre = "Gestion des "+response.data.data.length +" commentaires"
                    })

            },
            getDestroy(info){
                let options = {
                    okText: 'Supprimer',
                    cancelText: 'Fermer',
                    type:'hard',
                    verification: 'Supprimer',
                    verificationHelp: 'Tapez [+:verification] avant de confirmer',
                    message: info.comment,
                    clicksCount: 1,
                    backdropClose: true,
                };
                this.$dialog.confirm(options.message,options)
                    .then(() => {
                        axios.delete('/api/administration/commentaires/'+info.id)
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
            this.$parent.titre = "Chargements ..."
            this.getInfo()
        }
    }
</script>