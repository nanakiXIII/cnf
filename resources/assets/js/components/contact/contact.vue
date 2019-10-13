<template>
    <div>
        <div class="container mt-3" v-show="info == true">
            <div class="row bg-white p-3 text-center">
                <div class="col-md-4">
                    <a href="https://twitter.com/chuushin_kira" target="_blank" class="colorise">
                        <h3>
                            <i class="fab fa-twitter p-1"></i>Twitter
                        </h3>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="https://discord.gg/fVQhnbG" class="colorise" target="_blank">
                        <h3>
                            <i class="fab fa-discord p-1"></i> Discord
                        </h3>
                    </a>
                    <small v-if="data != null" class="text-success">{{ data.members.length}} membres en ligne</small>
                </div>
                <div class="col-md-4">
                    <h3>
                        <i class="fas fa-envelope-open-text p-1"></i> Formulaire
                    </h3>
                    <small>Prochainement</small>
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
                data:null,
                info:false,
            }
        },
        props: {

        },
        computed: {


        },

        methods:{
            discord(){
                let discord_key = '336137805938032640';
                var myHeaders = new Headers();

                var myInit = { method: 'GET',
                    headers: myHeaders};

                fetch('https://discordapp.com/api/guilds/336137805938032640/widget.json')
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error("HTTP error " + response.status);
                        }
                        return response.json();
                    })
                    .then((json) => {
                        this.data = json
                    });




            }
        },
        beforeCreate(){
            this.$parent.titre = "Nous Contacter"
        },
        created(){

        },
        mounted(){
            this.info = true;
            this.discord()
        },
    }
</script>