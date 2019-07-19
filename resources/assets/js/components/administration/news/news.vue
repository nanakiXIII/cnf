<template>
    <div class="container mt-5">
        <div class="btn-group btn-group-justified" role="group" aria-label="...">
            <div class="btn-group" role="group">
                <router-link :to="{name:'AdminNewNews'}"
                             type="button"
                             class="btn btn-default"
                >
                    <i class="fas fa-plus text-success"></i> Nouvelle News
                </router-link>
                <button type="button"
                        class="btn btn-default"
                        v-bind:class="{'colorise':(type === 'all')}"
                        @click="type = 'all'"
                >
                    <i class="fas fa-globe"></i> All
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button"
                        class="btn btn-default"
                        v-bind:class="{'colorise':(type === 'Animes')}"
                        @click="type = 'Animes'"
                >
                    <i class="fas fa-video"></i> Animés
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button"
                        class="btn btn-default"
                        v-bind:class="{'colorise':(type === 'Scantrad')}"
                        @click="type = 'Scantrad'"
                >
                    <i class="fas fa-paint-brush"></i> Scantrad</button>
            </div>
            <div class="btn-group" role="group">
                <button type="button"
                        class="btn btn-default"
                        v-bind:class="{'colorise':(type === 'Light-Novel')}"
                        @click="type = 'Light-Novel'"
                >
                    <i class="fas fa-book-open"></i> Light Novel
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button"
                        class="btn btn-default"
                        v-bind:class="{'colorise':(type === 'Visual-Novel')}"
                        @click="type = 'Visual-Novel'"
                >
                    <i class="fas fa-gamepad"></i> Visual Novel
                </button>
            </div>
        </div> <!-- Choix du type -->
        <div class="row">
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col text-center">Visible</th>
                    <th scope="col text-center">Titre</th>
                    <th scope="col text-center">Type</th>
                    <th scope="col text-center">Date Publication</th>
                    <th scope="col text-center">Options</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="n in news" v-if="type == 'all' || type == n.type">
                    <td v-bind:class="{ 'table-success': n.publication == '1', 'table-warning ': n.publication == '0' }">
                        <span class="text-primary" v-if="n.publication == 0">Non</span>
                        <span class="text-primary" v-if="n.publication == 1">Oui</span>
                    </td>
                    <td>
                        {{n.titre}}
                    </td>
                    <td class="text-center">
                        {{n.type}}
                    </td>
                    <td class="text-center">
                        <template v-if="n.publication == 1">
                            {{n.publish_at.date | moment("dddd Do MMMM YYYY") }}
                        </template>
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <router-link :to="{ name: 'AdminModNews', params: { id:n.id, slug:n.slug } }" class="no-decoration">
                                <button type="button" class="btn btn-secondary">Modifier</button>
                            </router-link>
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
                type:'all'
            }


        },
        computed: {

        },
        watch:{

        },
        methods: {
            getNews(){
                axios.get('/api/administration/news')
                    .then(response => {
                        this.news = response.data.data
                        this.$parent.titre = "Gestion des news"
                    })

            },
            getDestroy(info){
                let options = {
                    okText: 'Confirmer',
                    cancelText: 'Fermer',
                    type:'hard',
                    verification: 'Supprimer',
                    verificationHelp: 'Tapez [+:verification] avant de confirmer',
                    message: 'Etes-vous sur de vouloir supprimer '+info.titre,
                    clicksCount: 1,
                    backdropClose: true,
                };
                this.$dialog.confirm(options.message,options)
                    .then(() => {
                        axios.delete('/api/administration/news/'+info.id)
                            .then(response =>{
                                if(response.data.success == true){
                                    this.getNews()
                                }else{

                                }
                            })
                    })
            }

        },
        mounted(){
            this.$parent.titre = "Chargements ..."
            this.getNews()
        }
    }
</script>