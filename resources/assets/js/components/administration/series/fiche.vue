<template>
    <div class="container mt-1">
        <div class="card mt-4">
            <div class="card-body">
                <form v-if="info" v-on:submit.prevent="getSubmit(info)" enctype="multipart/form-data">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="titre">Titre</span>
                        </div>
                        <input type="text" class="form-control" name="titre" v-model="info.titre">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="titre_original">Titre Original</span>
                        </div>
                        <input type="text" class="form-control" name="titre_original" v-model="info.titre_original">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="titre_alternatif">Titre Alternatif</span>
                        </div>
                        <input type="text" class="form-control" name="titre_alternatif" v-model="info.titre_alternatif">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="studio">Studio</span>
                        </div>
                        <input type="text" class="form-control" name="studio" v-model="info.studio">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="annee">Date de production</span>
                        </div>
                        <input type="text" class="form-control" name="annee" v-model="info.annee">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="etat">Etat</label>
                        </div>
                        <select class="custom-select" id="etat" name="etat" v-model="info.etat">
                            <option value="0">En Cours</option>
                            <option value="1">Terminé</option>
                            <option value="2">Abandonné</option>
                            <option value="3">Licencié</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="type">Type</label>
                        </div>
                        <select class="custom-select" id="type" name="type" v-model="info.type">
                            <option value="Animes">Animes</option>
                            <option value="Scantrad">Scantrad</option>
                            <option value="Light-Novel">Light-Novel</option>
                            <option value="Visual-Novel">Visual-Novel</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="episode">Episodes</span>
                        </div>
                        <input type="text" class="form-control" name="episode" v-model="info.episode">
                    </div>
                    <div class="input-group mb-3" v-if="info.type != 'Animes' && info.type != 'Visual-Novel'">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="scan">Tomes</span>
                        </div>
                        <input type="number" min="0" class="form-control" name="scan" v-model="info.scan">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Synopsis</span>
                        </div>
                        <textarea class="form-control" v-model="info.synopsis" name="synopsis" rows="8"></textarea>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Staff</span>
                        </div>
                        <textarea class="form-control text-center" v-model="info.staff" name="staff" rows="8"></textarea>
                    </div>
                    <div class="row mb-3">
                        <li class="list-group-item col-md-3" v-for="genre in genres">
                            {{genre.name}}
                            <label class="switch ">
                                <input type="checkbox" class="success" :value="genre.id" name="genres[]" v-model="info.genresId">
                                <span class="slider round"></span>
                            </label>
                        </li>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="publication">Visible</label>
                        </div>
                        <select class="custom-select" id="publication" name="publication" v-model="info.publication">
                            <option value="0">Non</option>
                            <option value="1">Oui</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="image">Image</label>
                            <input type="file" class="form-control-file" ref="image" id="image" name="image" v-on:change="onFileChange">
                            <i class="fa fa-check" v-if="info.image != ''"> Image disponible</i>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="banniere">Bannière</label>
                            <input type="file" class="form-control-file" ref="banniere" id="banniere" name="auteur" v-on:change="onFileChange">
                            <i class="fa fa-check" v-if="info.banniere != ''"> Bannière disponible</i>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-success btn-lg btn-block mt-3">Enregistrer</button>

                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                info:null,
                genres:null,
                image:null,
                banniere:null
            }


        },
        props:['user'],
        computed: {

        },
        watch:{

        },
        methods: {
            onFileChange: function onFileChange(e) {
                e.preventDefault();
                if(e.target.id == 'image'){
                    this.image = this.$refs.image.files[0];
                }else{
                    this.banniere = this.$refs.banniere.files[0];
                }
            },
            getSubmit(info){
                let formData = new FormData();
                let currentObj = this;
                formData.append('_method', 'put');
                formData.append('serie', JSON.stringify(this.info));
                formData.append('image', this.image);
                formData.append('banniere', this.banniere);
                axios.post('/api/administration/series',formData)
                    .then(function (response) {
                        currentObj.$router.push({name: 'AdminSerie'})
                    })
                    .catch(function (error) {
                        console.log(error)
                    });
            },
           getInfo(slug, id){
                axios.get('/api/administration/series/'+slug+'/'+id)
                    .then(response => {
                        this.info = response.data.data
                        this.$parent.titre = "Modification du projet "+this.info.titre
                    })

            },
            getGenres(){
                axios.get('/api/administration/genres')
                    .then(response => {
                        this.genres = response.data
                    })

            },

        },
        mounted(){
            let slug = encodeURIComponent(this.$route.params.slug);
            let id = this.$route.params.id;
            this.getInfo(slug, id)
            this.getGenres()
            this.$parent.titre = "Chargement ..."
        }
    }
</script>