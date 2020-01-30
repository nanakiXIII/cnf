<template>
    <div>
        <div class="bg-img pt-3 pb-3 mb-3">
            <div class="container" >
                <div class="row">
                    <div class="col-md-1" v-if="loading">
                        <div class="spinner-border text-warning" style="width: 2rem; height: 2rem;" role="status">
                            <span class="sr-only">Chargement ...</span>
                        </div>
                    </div>
                    <div class=" col-md-3 mb-3" v-bind:class="[ loading ? 'offset-8' : 'offset-9']">

                        <vc-date-picker
                                mode='range'
                                v-model='range'
                                :max-date="new Date()"

                        ></vc-date-picker>

                    </div>
                </div>
                <div class="row" v-if="stat.userType">
                    <div class="col-md-4 col-sm-4">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Nouveaux visiteurs</h5>
                                        <span class="h2 font-weight-bold mb-0">{{stat.userType[0].sessions}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                            <i class="fas fa-user-secret"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-1 mb-0 text-muted text-sm" v-if="userType != null">
                                    <span class="mr-2" v-bind:class="[ userType[0].sessions < stat.userType[0].sessions ? 'text-success' : 'text-danger']">
                                        <i class="fa fa-arrow-up" v-if="userType[0].sessions < stat.userType[0].sessions"></i>
                                        <i class="fa fa-arrow-down" v-else=""></i>
                                        {{ stat.userType[0].sessions - userType[0].sessions}}
                                    </span>
                                    <span class="text-nowrap">Nouveaux visiteurs</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Anciens visiteurs</h5>
                                        <span class="h2 font-weight-bold mb-0">{{stat.userType[1].sessions}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-1 mb-0 text-muted text-sm" v-if="userType != null">
                                    <span class="mr-2" v-bind:class="[ userType[1].sessions < stat.userType[1].sessions ? 'text-success' : 'text-danger']">
                                        <i class="fa fa-arrow-up" v-if="userType[1].sessions < stat.userType[1].sessions"></i>
                                        <i class="fa fa-arrow-down" v-else=""></i>
                                        {{ stat.userType[1].sessions - userType[1].sessions}}
                                    </span>
                                    <span class="text-nowrap">Anciens visiteurs</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row ">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Visiteurs total</h5>
                                        <span class="h2 font-weight-bold mb-0">{{stat.userType[1].sessions + stat.userType[0].sessions}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-1 mb-0 text-muted text-sm" v-if="userType != null">
                                    <span class="mr-2" v-bind:class="[ (userType[1].sessions + userType[0].sessions) < (stat.userType[1].sessions + stat.userType[0].sessions) ? 'text-success' : 'text-danger']">
                                        <i class="fa fa-arrow-up" v-if="(userType[1].sessions + userType[0].sessions) < (stat.userType[1].sessions + stat.userType[0].sessions)"></i>
                                        <i class="fa fa-arrow-down" v-else=""></i>
                                        {{ (stat.userType[1].sessions + stat.userType[0].sessions) - (userType[1].sessions + userType[0].sessions)}}
                                    </span>
                                    <span class="text-nowrap">Visiteurs total</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mb-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Visiteurs
                        </div>
                        <div class="card-body">
                            <div id="courbe"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Principaux sites référents
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center" v-for="ref in stat.referents">
                                {{ref.url}}
                                <span class="badge badge-primary badge-pill">{{ref.pageViews}}</span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-img pt-3 pb-3 mb-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-3" v-for="nav in stat.navigateur">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">{{nav.browser}}</h5>
                                        <span class="h2 font-weight-bold mb-0">{{nav.sessions}}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="icon icon-shape text-success rounded-circle " v-if="nav.browser === 'Chrome'">
                                            <i class="fab fa-chrome shadow"></i>
                                        </div>
                                        <div class="icon icon-shape text-warning rounded-circle" v-if="nav.browser === 'Firefox'">
                                            <i class="fab fa-firefox shadow"></i>
                                        </div>
                                        <div class="icon icon-shape text-primary rounded-circle" v-if="nav.browser === 'Edge'">
                                            <i class="fab fa-edge shadow"></i>
                                        </div>
                                        <div class="icon icon-shape text-info rounded-circle" v-if="nav.browser === 'Safari'">
                                            <i class="fab fa-safari shadow"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ApexCharts from 'apexcharts'
    export default {
        data(){
            return {
                stat:{},
                range: {
                    start:new Date(),
                    end:new Date()
                },
                chart:'',
                loading:true,
                sync:false,
                userType:null
            }
        },
        watch:{
            range(){
                this.sync = true;
                this.getStat();
            }
        },
        computed: {


        },

        methods:{
            getStat(){
                if(this.sync === true){
                    this.userType = this.stat.userType;
                    this.sync = false
                }
                this.loading = true;
                const {start, end} = this.range
                axios.post('/api/administration/statistique/visites', {start, end})
                    .then(response =>{
                        if(response.data){
                            this.stat = response.data
                            this.visites()
                            this.loading = false;
                        }
                    })
            },
            visites(){
                var option =  {
                    chart: {
                        height: 200,
                        type: 'area',
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    series: [{
                        name: 'Visites',
                        data: this.stat.userPeriode.visitors
                    }],

                    xaxis: {
                        type: 'category',
                        categories: this.stat.userPeriode.date,
                    },
                    tooltip: {
                        x: {
                            format: 'dd/MM/yy HH:mm'
                        },
                    }
                }
                if (this.chart != ''){
                    this.chart.destroy()
                }
                this.chart = new ApexCharts(
                    this.$el.querySelector('#courbe'),
                    option
                );
                this.chart.render();
                //setTimeout(function () {  }.bind(this), 500)
            },
        },
        beforeCreate(){

        },
        created(){
            this.$parent.titre = "Statistiques des visiteurs"

        },
        mounted(){
           this.getStat();
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
    .card-stats .card-body {
        padding: 1rem 1.5rem;
    }
    .icon {
        width: 3rem;
        height: 3rem;
    }

    .icon i {
        font-size: 2.25rem;
    }

    .icon-shape {
        display: inline-flex;
        padding: 12px;
        text-align: center;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
    }

    .icon-shape i {
        font-size: 2rem;
    }
</style>