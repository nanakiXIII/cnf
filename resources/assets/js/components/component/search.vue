<!--<div class="bg-img p-4">
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
                                     <div class="media-body">
                                         <h5 class="mt-0 mb-1">{{ result.titre }}</h5>
                                     </div>
                                 </li>
                             </ul>
                         </div>
                         <div class="btn-group btn-group-justified" role="group" aria-label="Recherche des types">
                             <div class="btn-group" role="group">
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
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>-->
<script>
    export default {
        data(){
            return {
                series: {},
                isOpen: false,
                results: [],
                search: '',
                isLoading: false,
                arrowCounter: 0,
            };
        },
        props: {
            type : String,
            isAsync: {
                type: Boolean,
                required: false,
                default: false,
            },
        },
        watch:{
            items: function (val, oldValue) {
                if (val.length !== oldValue.length) {
                    this.results = val;
                    this.isLoading = false;
                }
            },
        },
        computed: {
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
            getInfo(){
                axios.get('/api/projets')
                    .then(response => {
                        this.series = response.data.data
                    })
            },
            call(){

                if (this.$store.getters.isAuthenticated) {
                    this.$store.dispatch('SerieRequestLog', {type});
                }
                else {
                    this.$store.dispatch('SerieRequest', {type});
                }

            }
        },
        mounted(){
            this.getInfo()
            this.$parent.titre = "Nos projets"
            document.addEventListener('click', this.handleClickOutside)
        },
        destroyed() {
            document.removeEventListener('click', this.handleClickOutside)
        }
    }
</script>