<template>
    <div class="row ">
        <div class="col-md-12">
            <div class="row border bg-white">
                <div class="col-md-12 p-4">
                    <label class="my-1 mr-2" for="theme" >Changer de thème</label>
                    <select class="custom-select custom-select-lg mb-3" v-model="theme" id="theme">
                        <option value="" selected>Selectionnez un theme</option>
                        <option value="light">Clair</option>
                        <option value="dark">Sombre</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                theme: ''
            }
        },
        watch:{
            theme(){
                if(this.theme != ''){
                    this.information('theme')
                }
            },
        },
        computed: {
            user(){
                return this.$store.getters.getProfile
            }
        },
        methods: {
            information: function (action) {
                this.$parent.theme = this.theme
                const { theme } = this;
                this.$store.dispatch('compteRequest', { theme, action })

            },

        },
        mounted(){

        }
    }
</script>