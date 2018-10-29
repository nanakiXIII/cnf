<template>
    <div>
        <div class="bg-img p-4">
            <div class="container">
                <div class="titre">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="autocomplete">
                                <input
                                        class="form-control py-2 border-right-0 border col-md-12"
                                        placeholder="Recherche"
                                        type="text"
                                        @input="onChange"
                                        v-model="search"
                                        @keydown.down="onArrowDown"
                                        @keydown.up="onArrowUp"
                                        @keydown.enter="onEnter"
                                />
                                <ul
                                        id="autocomplete-results"
                                        v-show="isOpen"
                                        class="autocomplete-results list-unstyled"
                                >
                                    <li
                                            class="loading"
                                            v-if="isLoading"
                                    >
                                        Loading results...
                                    </li>
                                    <li
                                            v-else
                                            v-for="(result, i) in results"
                                            :key="i"
                                            @click="setResult(result)"
                                            class="autocomplete-result media"
                                            :class="{ 'is-active': i === arrowCounter }"
                                    >
                                        <img class="mr-3" :src="result.image" width="100px" alt="Generic placeholder image">
                                        <div class="media-body">
                                            <h5 class="mt-0 mb-1">{{ result.titre }}</h5>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-3">
            <div class="row mt-4 border bg-white" v-if="series == ''">
                <div class="col-md-12 text-center p-4">
                    Pas de série pour le moment
                </div>
            </div>
            <div class="row mt-4 border bg-white relative" v-for="serie in series">
                <template v-if="serie">
                    <div class="triangle" v-if="serie.abo">
                        <p><i class="fas fa-bookmark"></i></p>
                    </div>
                    <div class="col-md-4 img-news">
                        <img :src="serie.image">
                    </div>
                    <div class="col-md-8 ">
                        <div class="card-block px-1">
                            <router-link :to="{ name: 'serieDetail', params: { slug: serie.slug} }" class="title">
                                <h4 class="card-title pb-1 pt-3 center-mobile"><b>{{ serie.titre }}</b></h4>
                            </router-link>
                            <p class="card-text bg-grey col-md-12 p-3 mb-3">
                                <span class='info-tooltip'>Auteur</span> {{ serie.auteur }}<br>
                                <span class='info-tooltip'>Studio</span> {{ serie.studio }}<br>
                                <span class='info-tooltip'>Année</span> {{ serie.annee }} <br>
                                <span class='info-tooltip'>Etat</span>
                                <span class="text-primary" v-if="serie.etat == 0">En Cours</span>
                                <span class="text-success" v-if="serie.etat == 1">Terminé</span>
                                <span class="text-warning" v-if="serie.etat == 2">Abandonné</span>
                                <span class="text-danger" v-if="serie.etat == 3">Licencié</span>
                                <br>
                                <span class='info-tooltip'>Genres</span>
                                <span v-for="genre in serie.genres">
                                {{ genre.name}}
                            </span>
                            </p>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                serie: ['dd'],
                isOpen: false,
                results: [],
                search: '',
                isLoading: false,
                arrowCounter: 0,
            };
        },
        props: {
            isAsync: {
                type: Boolean,
                required: false,
                default: false,
            },
        },
        watch:{
            '$route.params.type': function (type) {
            this.call(type)
            },
            items: function (val, oldValue) {
                if (val.length !== oldValue.length) {
                    this.results = val;
                    this.isLoading = false;
                }
            },
        },
        computed: {
            series(){
                return this.$store.getters.getSerie;

            },
        },

        methods:{
            onChange() {
                // Let's warn the parent that a change was made
                this.$emit('input', this.search);

                // Is the data given by an outside ajax request?
                if (this.isAsync) {
                    this.isLoading = true;
                } else {
                    // Let's  our flat array
                    this.filterResults();
                    this.isOpen = true;
                }
            },

            filterResults() {
                // first uncapitalize all the things
                this.results = this.series.filter((item) => {
                    return item.titre.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                });
            },
            setResult(result) {
                this.search = result.titre;
                this.$router.push({ name: 'serieDetail', params: { slug: result.slug} })
                this.isOpen = false;
            },
            onArrowDown(evt) {
                if (this.arrowCounter < this.results.length) {
                    this.arrowCounter = this.arrowCounter + 1;
                }
            },
            onArrowUp() {
                if (this.arrowCounter > 0) {
                    this.arrowCounter = this.arrowCounter -1;
                }
            },
            onEnter() {
                this.search = this.results[this.arrowCounter].titre;
                this.$router.push({ name: 'serieDetail', params: {slug: this.results[this.arrowCounter].slug} })
                this.isOpen = false;
                this.arrowCounter = -1;
            },
            handleClickOutside(evt) {
                if (!this.$el.contains(evt.target)) {
                    this.isOpen = false;
                    this.arrowCounter = -1;
                }
            },
            call(){
                let type = this.$route.params.type;
                this.type = type;
                this.$parent.titre = "Liste des "+this.type
                if(!type){
                    this.$router.push('/');
                }
                if(type === 'Animes' || type === 'Scantrad' || type === 'Light-Novel' || type === 'Visual-Novel'){
                    if (this.$store.getters.isAuthenticated) {
                        this.$store.dispatch('SerieRequestLog', {type});
                    }
                    else {
                        this.$store.dispatch('SerieRequest', {type});
                    }

                }
                else{
                    this.$router.push('/');
                }
            }
        },
        mounted(){
            this.call()
            this.$parent.titre = "Liste des "+this.type
            document.addEventListener('click', this.handleClickOutside)
        },
        destroyed() {
            document.removeEventListener('click', this.handleClickOutside)
        }
    }
</script>
