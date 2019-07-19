<template>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <form v-on:submit.prevent="getSubmit()">
                    <div class="form-group row">
                        <label for="titreNews" class="col-sm-2 col-form-label">Titre</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="titreNews" placeholder="Titre" v-model="news.titre" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="type" class="col-sm-2 col-form-label" >Type</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="type" v-model="news.type">
                                <option value="all">All</option>
                                <option value="Animes">Animes</option>
                                <option value="Scantrad">Scantrad</option>
                                <option value="Light-Novel">Light novel</option>
                                <option value="Visual-Novel">Visual novel</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="serie" class="col-sm-2 col-form-label" >Serie</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="serie" v-model="news.serie_id" @change="selectChange($event)">
                                <option value="0" data-key="pp">Aucune</option>
                                <option v-for="(serie, key) in series" :value="serie.id" :key="key" @click="news.key = key" :data-key="key" v-if="news.type == 'all' || news.type == serie.type">[{{serie.type}}] {{serie.titre}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fichiers" class="col-sm-2 col-form-label" >Fichiers</label>
                        <div class="col-sm-10 ">
                            <div class="card border" v-for="saison in serieSelect.saisons">
                                <div class="card-header">
                                    {{ saison.type }} {{saison.numero}} : {{saison.name}}
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush mt-0">
                                        <li class="list-group-item" v-for="e in saison.episodes">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" :value="e.id" :id="'fichiers'+e.id" name="fichiers[]" v-model="news.fichier_id">
                                                <label class="form-check-label" :for="'fichiers'+e.id">
                                                    {{ e.type }} {{e.numero}} : {{ e.name}}
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staff" class="col-sm-2 col-form-label">Staff</label>
                        <div class="col-sm-10">
                            <textarea class="form-control text-center" id="staff" v-model="news.staff" name="staff" rows="8"></textarea>
                        </div>
                    </div>
                    <div class="form-group" >
                        <div class="row">
                            <vue-editor v-model="news.contenu" useCustomImageHandler @imageAdded="handleImageAdded"
                                        id="editor1">
                            </vue-editor>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="publication" class="col-sm-2 col-form-label" >Visible</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="publication" v-model="news.publication">
                                <option value="0">Non</option>
                                <option value="1">Oui</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary">Sign in</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</template>
<script>
    import { VueEditor } from "vue2-editor";
    export default {
        components: {
            VueEditor
        },
        data(){
            return {
                news:{},
                series:{},
                serieSelect:{}

            }


        },
        computed: {

        },
        watch:{

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
            getSerie(){
                axios.get('/api/administration/series')
                    .then(response => {
                        this.series = response.data.data
                    })

            },
            getNews(id,slug){
                axios.get('/api/administration/news/'+id+'/'+slug)
                    .then(response => {
                        this.news = response.data.data
                        this.$parent.titre = response.data.titre

                    })

            },
            handleImageAdded: function(file, Editor, cursorLocation, resetUploader) {
                var formData = new FormData();
                formData.append("image", file);

                axios({
                    url: "/api/administration/news/image",
                    method: "POST",
                    data: formData
                })
                    .then(result => {
                        let url = result.data.url; // Get url from response
                        this.news.image = url;
                        Editor.insertEmbed(cursorLocation, "image", url);
                        resetUploader();
                    })
                    .catch(err => {
                        console.log(err);
                    });
            },
            selectChange(event) {
                let key = document.getElementById("serie").options[document.getElementById("serie").selectedIndex].dataset.key
                console.log(key)
                if (key != "undefined"){
                    if(key == 'pp'){
                        this.news.serie_id = 0;
                        this.news.staff = ""
                    }else{
                        this.news.serie_id = this.series[key].id;
                        this.news.staff = this.series[key].staff;
                        this.serieSelect = this.series[key];
                    }
                }
            },
            getSubmit(){
                const data = this.news
                if (this.news.contenu != ""){
                    axios.put('/api/administration/news', data)
                        .then(response =>{
                            if(response.data.success == true){
                                this.$router.push({name: 'AdminNews'})
                            }else{

                            }
                        })
                }
            },
        },
        beforeMount(){
            this.$parent.titre = "Chargements..."
            let slug = encodeURIComponent(this.$route.params.slug);
            let id = this.$route.params.id;
            this.getNews(id,slug)
            this.getSerie()
        },
        updated(){
            if (this.news.serie_id){
                this.selectChange()
            }

        }
    }
</script>
<style>
    .quillWrapper {
        display: inline-grid;
        width: 100%;
    }
    #editor1{
        height: 500px;
    }
</style>