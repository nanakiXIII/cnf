<template>
	<div>
		<div class="pt-2" v-if="serie.saisons != '' && serie.episode != 0">
			<div class="container mt-3">
				<div class="categorie">
					<i class="icon fas fa-file-download"></i> <b>Téléchargements</b>
				</div>
				<ul class="main-list" v-for="(saison, key) in serie.saisons">
					<li class="parent">
						<a class="titre cursor" data-toggle="collapse" :data-target="'#saison'+saison.id" v-if="saison.nosaison === 0">
							{{saison.type}} {{saison.numero}} : {{ saison.name }}
						</a>
						<ul class="list-fichier collapse" :id="'saison'+saison.id" v-bind:class="[ key == 0 || saison.nosaison  ? 'show' : '']">
							<li class="manga-list cursor" v-for="episode in saison.episodes" @click="modal(episode, saison)">
								<a>{{ episode.type }} {{ episode.numero}} : {{ episode.name }}</a>
								<span class="date">
									{{ episode.add_to.date | moment('MMMM YYYY') }}
								</span>
							</li>
						</ul>
					</li>
				</ul>
				<modal name="fichier"
					   height="auto">
					<div class=" bg-grey row">
						<div class="col-md-4">
							<img :src="fichier.image" alt="" width="100%">
						</div>
						<div class="col-md-8 text-center">
							<div class="pt-2">
								<b >{{ fichier.type }} {{fichier.numero}} : {{fichier.name}}</b>
							</div>
							<div class="pt-3" v-if="serie.type === 'Animes'">
								<a v-show="fichier.dvd != 'non'" :href="fichier.dvd" @click="dl('dvd',fichier)" class="btn" v-bind:class="[ fichier.downloaddvd ? 'btn-outline-success' : 'btn-outline-colorise']" target="_blank">DVD</a>
								<a v-show="fichier.hd != 'non'"  :href="fichier.hd" @click="dl('hd', fichier)" class="btn" v-bind:class="[ fichier.downloadhd ? 'btn-outline-success' : 'btn-outline-colorise']" target="_blank">720P</a>
								<a v-show="fichier.fhd != 'non'" :href="fichier.fhd" @click="dl('fhd',fichier)" class="btn" v-bind:class="[ fichier.downloadfhd ? 'btn-outline-success' : 'btn-outline-colorise']" target="_blank">1080P</a>
								<router-link v-show="fichier.etat >= 4" :to="{name:'streaming', params:{saison: info.numero, episode:fichier.numero}}" class="btn" v-bind:class="[ fichier.stream ? 'btn-outline-success' : 'btn-outline-colorise']">Streaming</router-link>
							</div>
							<div class="pt-3" v-if="serie.type === 'Scantrad'">
								<a v-show="fichier.hd != 'non'"  :href="fichier.hd" @click="dl('hd', fichier)" class="btn" v-bind:class="[ fichier.downloadhd ? 'btn-outline-success' : 'btn-outline-colorise']" target="_blank">Télécharger </a>
								<router-link v-show="serie.type == 'Scantrad' && fichier.etat == 5" :to="{name:'lecture', params:{saison: info.numero, episode:fichier.numero}}" class="btn" v-bind:class="[ fichier.stream ? 'btn-outline-success' : 'btn-outline-colorise']">Lecture en ligne</router-link>
							</div>
							<div class="pt-3" v-if="serie.type === 'Light-Novel'">
								<a v-show="fichier.hd != 'non'"  :href="'/storage/'+fichier.hd" @click="dl('hd', fichier)" class="btn" v-bind:class="[ fichier.downloadhd ? 'btn-outline-success' : 'btn-outline-colorise']" target="_blank" download="">Télécharger </a>
								<router-link :to="{name:'lightNovel', params:{saison: info.numero, episode:fichier.numero}}" class="btn" v-bind:class="[ fichier.stream ? 'btn-outline-success' : 'btn-outline-colorise']">Lecture en ligne</router-link>
							</div>
						</div>
					</div>
				</modal>
			</div>
		</div>

	</div>



</template>

<script>
    export default {
        data(){
            return {
				fichier:{},
				info:{}
            }
        },
        props: ['serie'],
        computed: {


        },

        methods:{
            modal(episode, saison){
                this.fichier = episode
				this.info = saison
                this.$modal.show('fichier')
			},
            dl:function(qualiter, episode){
                var episode_id = episode.id;
                var serie_id = this.serie.id;
                axios.post('/api/telechargements', {serie_id, qualiter, episode_id})
                    .then(response => {
                        this.getInfo(this.$route.params.type, this.$route.params.slug)
                    })

            },

        },
        beforeCreate(){

        },
        created(){

        },
        mounted(){
        },
    }
</script>
<style>
	ul.main-list{
		display: flex;
		flex-direction: column;
	}
	li.parent{
		position: relative;
		list-style: none;
		line-height: 50px;
		padding:15px 0;

	}
	li.parent .titre{
		font-weight: 600;
		margin-left: -10px;
		font-size: 20px;
	}
	li.parent i.ico{
		position: absolute;
		right: 15px;
		top: 25px;
		font-size: 18px;
		pointer-events: none;
	}
	ul.list-fichier{
		display: block;
	}
	li.manga-list{
		list-style: none;
		position: relative;
		word-break: break-all;
		word-wrap: break-word;
	}
	li.manga-list:hover{
		color: #eb3349;
	}
	li.manga-list:before{
		content: "";
		display: inline-block;
		width: 3px;
		height: 100%;
		background:red;
		position: absolute;
		left: -30px;
	}
	li.manga-list:after{
		content: "";
		position: absolute;
		display: block;
		height: 1px;
		background-color:#ebebeb;
		width: 100%;
		opacity: 1;
		visibility: visible;
		left: 0;
	}
	span.date{
		text-transform: capitalize;
		position: absolute;
		top: 50%;
		right: 0;
		-webkit-transform: translateY(-50%);
		-ms-transform: translateY(-50%);
		-o-transform: translateY(-50%);
		transform: translateY(-50%);
	}
</style>