<template>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-offset-2 col-sm-12 mt-5">
                <ul class="event-list">
                    <li v-for="n in news.data">
                        <time :datetime="n.publish_at | moment('Do MMMM YYYY')">
                            <span class="day">{{ n.publish_at | moment('Do') }}</span>
                            <span class="month">{{ n.publish_at | moment('MMM') }}</span>
                            <span class="year">{{ n.publish_at | moment('YYYY') }}</span>
                            <span class="time">ALL DAY</span>
                        </time>
                        <img alt="Chargements ..." :src="n.image" />
                        <div class="info">
                            <h2 class="title">{{ n.titre }}</h2>
                            <p class="desc"></p>
                        </div>
                        <div class="social">
                            <ul>
                                <li class="facebook" style="width:33%;"><a href=""><span class="fa fa-search"></span></a></li>
                                <li class="twitter" style="width:34%;"><a href=""><span class="fa fa-cog"></span></a></li>
                                <li class="google-plus" style="width:33%;"><a href=""><span class="fa fa-trash"></span></a></li>
                            </ul>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </div>

</template>
<script>
    export default {
        data(){
            return {
                data:{},
                from:'1',
                contenu:{},
                page:1,
                lastPage:false,
                nextPage:1
            }


        },
        computed: {
            news(){
                return this.$store.getters.getNews;
            },
            user(){
                return this.$store.getters.getProfile
            }
        },
        watch:{
            news(){
                this.page=this.news.pagination.current_page
                this.lastPage=this.news.pagination.last_page
                this.nextPage=this.news.pagination.current_page +1
                this.from = this.news.pagination.from +10
                this.contenu = Object.assign(this.data, this.news.data)
            }
        },
        methods: {
            next(){
                this.paginate()
            },
            paginate(){
                const { nextPage, page, from } = this
                this.$store.dispatch('NewsRequest', {nextPage, from});
            },
        },
        mounted(){
            this.$parent.titre = "Gestion des news"
            this.paginate()

        }
    }
</script>