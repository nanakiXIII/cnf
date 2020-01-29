<div class="pt-2" v-if="serie.saisons != '' || serie.episode != 0">
    <div class="container mt-3">
        <div class="categorie"><i class="icon fas fa-file-download"></i> <b>Téléchargements</b></div>
        <div class="card bg-transparent" v-for="saison in serie.saisons">
            <div class="card-header cursor" data-toggle="collapse" :data-target="'#saison'+saison.id" v-if="saison.nosaison == 0">
                <h5 class="mb-0 col-title" >
                    {{saison.type}} {{saison.numero}}: {{ saison.name }}
                </h5>
            </div>
            <div :id="'saison'+saison.id" class="collapse " aria-expanded="true" v-bind:class="[ saison.nosaison ? 'show' : '']">
                <div class=" bg-transparent" v-if="saison.episodes != ''">
                    <div class="media mt-3 border shadow-sm p-3 bg-white rounded" v-for="episode in saison.episodes">
                        <img class="align-self-center mr-3"  :src="episode.image" alt=""  style="max-height: 82px" v-if="episode.image != 'noImage.jpg'">
                        <img class="align-self-center mr-3"  :src="'https://via.placeholder.com/150?text='+episode.numero" alt=""  style="max-height: 82px" v-else="">
                        <div class="media-body">
                            <h5 class="mt-1">{{ episode.type }} {{ episode.numero }}: {{ episode.name }}</h5>
                            <p>

                            <div class="pull-right">
                                <a v-show="episode.dvd != 'non' && serie.type == 'Animes'" :href="episode.dvd" @click="dl('dvd',episode)" class="btn" v-bind:class="[ episode.downloaddvd ? 'btn-success' : 'btn-secondary']" target="_blank">DVD</a>
                                <a v-show="episode.hd != 'non' && serie.type == 'Animes'"  :href="episode.hd" @click="dl('hd', episode)" class="btn" v-bind:class="[ episode.downloadhd ? 'btn-success' : 'btn-secondary']" target="_blank">720P</a>
                                <a v-show="episode.fhd != 'non' && serie.type == 'Animes'" :href="episode.fhd" @click="dl('fhd',episode)" class="btn" v-bind:class="[ episode.downloadfhd ? 'btn-success' : 'btn-secondary']" target="_blank">1080P</a>
                                <router-link v-show="serie.type == 'Animes' && episode.etat >= 4" :to="{name:'streaming', params:{saison: saison.numero, episode:episode.numero}}" class="btn" v-bind:class="[ episode.stream ? 'btn-success' : 'btn-secondary']">Streaming</router-link>

                                <a v-show="episode.hd != 'non' && serie.type == 'Scantrad'"  :href="episode.hd" @click="dl('hd', episode)" class="btn" v-bind:class="[ episode.downloadhd ? 'btn-success' : 'btn-secondary']" target="_blank">Télécharger </a>
                                <router-link v-show="serie.type == 'Scantrad' && episode.etat == 5" :to="{name:'lecture', params:{saison: saison.numero, episode:episode.numero}}" class="btn" v-bind:class="[ episode.stream ? 'btn-success' : 'btn-secondary']">Lecture en ligne</router-link>

                                <a v-show="episode.hd != 'non' && serie.type == 'Light-Novel'"  :href="'/storage/'+episode.hd" @click="dl('hd', episode)" class="btn" v-bind:class="[ episode.downloadhd ? 'btn-success' : 'btn-secondary']" target="_blank" download="">Télécharger </a>
                                <router-link v-show="serie.type == 'Light-Novel'" :to="{name:'lightNovel', params:{saison: saison.numero, episode:episode.numero}}" class="btn" v-bind:class="[ episode.stream ? 'btn-success' : 'btn-secondary']">Lecture en ligne</router-link>
                            </div>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body text-center" v-else-if="saison.episode != '' && saison.nosaison != true">
                    Pas de téléchargements disponible pour le moment
                </div>
            </div>
        </div>
    </div>
</div>