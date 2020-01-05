<transition-group name="news" tag="div" class="col-md-12" enter-active-class="animated zoomIn" leave-active-class="animated zoomOut">
    <div class="card card-news mb-3 shadow" style="width: 100%!important; height: auto!important;" v-for="n in data" v-bind:key="n.id" v-if="n.contenu">
        <div class="row">
            <div class="col-md-3 img-news">
                <img :data-src="n.image" class="lazyload" alt="Chargement...">
            </div>
            <div class="col-md-9 ">
                <div class="card-block px-1">
                    <h4 class="card-title pb-3 pt-3 categorie">
                        <i class="icon fas fa-video" v-if="n.type == 'Animes'"></i>
                        <i class="icon fas fa-book-open" v-if="n.type == 'Light-novel'"></i>
                        <i class="icon fas fa-paint-brush" v-if="n.type == 'Scantrad'"></i>
                        <i class="icon fas fa-gamepad" v-if="n.type == 'Visual-novel'"></i>
                        <i class="icon fas fa-globe" v-if="n.type == 'all'"></i>
                        <b>{{ n.titre }}</b>
                    </h4>
                    <p class="card-text p-2" v-html="n.contenu.replace(/<[^>]*>/g, '').slice(0, 300)+' ...'">
                    </p>
                </div>
                <div class="footer">
                    <div class="row">
                        <div class="col-6">
                            <i class="fa fa-comments"></i> {{ n.comments.length}}
                            | <i class="fa fa-calendar"></i> {{ n.publish_at.date | moment('Do MMMM YYYY') }}
                        </div>
                        <div class="col-6 text-right">
                            <router-link :to="{name:'news', params:{slug:n.slug}}" :title="n.titre" class="colorise">
                                Lire la suite
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</transition-group>