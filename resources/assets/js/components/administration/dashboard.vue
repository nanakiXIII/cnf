<template>

    <div class="container mt-5">
        <div class="bg-white p-4 mb-3 shadow">
            <h2>Statistiques pour le mois de {{ Date.now() | moment('MMMM YYYY') }}</h2>
            <div class="row">
                <div class="col-md-6">
                    <div id="chart"></div>
                </div>
                <div class="col-md-6">
                    <div id="courbe"></div>
                </div>
                <div class="container"v-if="stat == null">
                    <div class="row mt-2">
                        <div class="col-md-12 text-center">
                            <div class="spinner-border mt-5" style="width: 6rem; height: 6rem;" role="status">
                                <span class="sr-only">Chargement ...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="bg-white p-4 mb-3 shadow">
            <h2>Informations du serveur</h2>
            <div class="row">
                <div class="col-4">
                    <div id="disque"></div>
                </div>
                <div class="col-4">
                    <div id="ram"></div>
                </div>
                <div class="col-4">
                    <div id="cpu"></div>
                </div>
                <div class="container"v-if="data == null">
                    <div class="row mt-2">
                        <div class="col-md-12 text-center">
                            <div class="spinner-border mt-5" style="width: 6rem; height: 6rem;" role="status">
                                <span class="sr-only">Chargement ...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <div class="wrimagecard wrimagecard-topimage">
                    <router-link :to="{name:'AdminNews'}">
                        <div class="wrimagecard-topimage_header text-center" style="background-color:rgba(250, 188, 9, 0.1) ">
                            <i class="fas fa-newspaper" style="color:#fabc09"></i>
                        </div>
                        <div class="wrimagecard-topimage_title text-center text-truncate">
                            <h4>News</h4>
                        </div>
                    </router-link>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="wrimagecard wrimagecard-topimage">
                    <router-link :to="{name:'AdminAvancements'}">
                        <div class="wrimagecard-topimage_header text-center" style="background-color:rgba(250, 188, 9, 0.1) ">
                            <i class="fas fa-seedling" style="color:#fabc09"></i>
                        </div>
                        <div class="wrimagecard-topimage_title text-center text-truncate">
                            <h4>Avancements</h4>
                        </div>
                    </router-link>
                </div>
            </div>
            <div class="offset-md-3 col-md-3 col-sm-4">
                <div class="wrimagecard wrimagecard-topimage">
                    <router-link :to="{name:'statistiqueVisite'}">
                        <div class="wrimagecard-topimage_header text-center" style="background-color:rgba(250, 188, 9, 0.1) ">
                            <i class="fas fa-chart-line" style="color:#fabc09"></i>
                        </div>
                        <div class="wrimagecard-topimage_title text-center text-truncate">
                            <h4>Statistiques</h4>
                        </div>
                    </router-link>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <div class="wrimagecard wrimagecard-topimage">
                    <router-link :to="{name:'AdminSerie'}">
                        <div class="wrimagecard-topimage_header text-center" style="background-color:rgba(51, 105, 232, 0.1) ">
                            <i class="fas fa-folder-open" style="color:#3369e8"></i>
                        </div>
                        <div class="wrimagecard-topimage_title text-center text-truncate">
                            <h4>Projets</h4>
                        </div>
                    </router-link>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="wrimagecard wrimagecard-topimage">
                    <router-link :to="{name:'AdminGenres'}">
                        <div class="wrimagecard-topimage_header text-center" style="background-color:rgba(51, 105, 232, 0.1) ">
                            <i class="fas fa-tags" style="color:#3369e8"></i>
                        </div>
                        <div class="wrimagecard-topimage_title text-center text-truncate">
                            <h4>Genres</h4>
                        </div>
                    </router-link>
                </div>
            </div>
            <div class="offset-md-3 col-md-3 col-sm-4">
                <div class="wrimagecard wrimagecard-topimage">
                    <router-link :to="{name:'AdminCommentaires'}">
                        <div class="wrimagecard-topimage_header text-center" style="background-color:rgba(51, 105, 232, 0.1) ">
                            <i class="fas fa-comments" style="color:#3369e8"></i>
                        </div>
                        <div class="wrimagecard-topimage_title text-center text-truncate">
                            <h4>Commentaires</h4>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <div class="wrimagecard wrimagecard-topimage">
                    <router-link :to="{name:'utilisateurs'}">
                        <div class="wrimagecard-topimage_header text-center" style="background-color:rgba(22, 160, 133, 0.1) ">
                            <i class="fas fa-users" style="color:#16A085"></i>
                        </div>
                        <div class="wrimagecard-topimage_title text-center text-truncate">
                            <h4>Membres</h4>
                        </div>
                    </router-link>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="wrimagecard wrimagecard-topimage">
                    <router-link :to="{name:'AdminPostes'}">
                        <div class="wrimagecard-topimage_header text-center" style="background-color:rgba(22, 160, 133, 0.1) ">
                            <i class="fas fa-chalkboard-teacher" style="color:#16A085"></i>
                        </div>
                        <div class="wrimagecard-topimage_title text-center text-truncate">
                            <h4>Postes</h4>
                        </div>
                    </router-link>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="wrimagecard wrimagecard-topimage">
                    <router-link :to="{name:'AdminRoles'}">
                        <div class="wrimagecard-topimage_header text-center" style="background-color:rgba(22, 160, 133, 0.1) ">
                            <i class="fas fa-user-tag" style="color:#16A085"></i>
                        </div>
                        <div class="wrimagecard-topimage_title text-center text-truncate">
                            <h4>Grades</h4>
                        </div>
                    </router-link>
                </div>
            </div>
            <div class="col-md-3 col-sm-4">
                <div class="wrimagecard wrimagecard-topimage">
                    <router-link :to="{name:'AdminPermissions'}">
                        <div class="wrimagecard-topimage_header text-center" style="background-color:rgba(22, 160, 133, 0.1) ">
                            <i class="fas fa-user-shield" style="color:#16A085"></i>
                        </div>
                        <div class="wrimagecard-topimage_title text-center text-truncate">
                            <h4>Permissions</h4>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-4">
                <div class="wrimagecard wrimagecard-topimage">
                    <router-link :to="{name:'gestionVideo'}">
                        <div class="wrimagecard-topimage_header text-center" style="background-color:rgba(173, 16, 16, 0.1) ">
                            <i class="fas fa-file-video" style="color:#ad1010"></i>
                        </div>
                        <div class="wrimagecard-topimage_title text-center text-truncate">
                            <h4>Streaming</h4>
                        </div>
                    </router-link>
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
                data:null,
                stat:null,
                from:'1',
                contenu:{},
                page:1,
                lastPage:false,
                nextPage:1
            }


        },
        computed: {
            user(){
                return this.$store.getters.getProfile
            }
        },
        watch:{

        },
        methods: {
            getInfo(){
                axios.get('/api/administration/dashboard')
                    .then(response => {
                        this.data = response.data
                        this.data.DDPourcentage = (1-( this.data.DDLibre / this.data.DDTotal )) *100;
                        this.disque()
                        this.ram()
                        this.cpu()
                    })

            },
            getStat(){
                axios.get('/api/administration/dashboard/statistiques')
                    .then(response => {
                        this.stat = response.data
                        this.affiche()
                        this.courbe()
                    })

            },
            disque(){
                var options = {
                    chart: {
                        height: 280,
                            type: "radialBar",
                    },
                    series: [Math.round(this.data.DDPourcentage)],
                    colors: ["#20E647"],
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                    size: '70%',
                                    background: "#293450"
                            },
                            track: {
                                dropShadow: {
                                    enabled: true,
                                        top: 2,
                                        left: 0,
                                        blur: 4,
                                        opacity: 0.15
                                }
                            },
                            dataLabels: {
                                show:true,
                                name: {
                                    offsetY: -5,
                                    color: "#fff",
                                    fontSize: "13px"
                                },
                                value: {
                                    color: "#fff",
                                    fontSize: "20px",
                                    show: true
                                },
                                total: {
                                    show: true,
                                    label: this.data.DDLibre +' '+this.data.DDUniter,
                                    color: "#fff",
                                }
                            }
                        }
                    },
                    fill: {
                        type: "gradient",
                            gradient: {
                            shade: "dark",
                                type: "vertical",
                                gradientToColors: ["#eb3349"],
                                stops: [0, 100]
                        }
                    },
                    stroke: {
                        lineCap: "round"
                    }
                };
                var chart = new ApexCharts(document.querySelector("#disque"), options);
                chart.render();
            },
            ram(){
                var options = {
                    chart: {
                        height: 280,
                        type: "radialBar",
                    },
                    series: [Math.round(this.data.memoryPourcentage)],
                    colors: ["#20E647"],
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: '70%',
                                background: "#293450"
                            },
                            track: {
                                dropShadow: {
                                    enabled: true,
                                    top: 2,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.15
                                }
                            },
                            dataLabels: {
                                show:true,
                                name: {
                                    offsetY: -5,
                                    color: "#fff",
                                    fontSize: "13px"
                                },
                                value: {
                                    color: "#fff",
                                    fontSize: "20px",
                                    show: true
                                },
                                total: {
                                    show: true,
                                    label: "Ram",
                                    color: "#fff",
                                }
                            }
                        }
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            shade: "dark",
                            type: "vertical",
                            gradientToColors: ["#eb3349"],
                            stops: [50, 100]
                        }
                    },
                    stroke: {
                        lineCap: "round"
                    }
                };
                var chart = new ApexCharts(document.querySelector("#ram"), options);
                chart.render();
            },
            cpu(){
                var options = {
                    chart: {
                        height: 280,
                        type: "radialBar",
                    },
                    series: [Math.round(this.data.cpu)],
                    colors: ["#20E647"],
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: '70%',
                                background: "#293450"
                            },
                            track: {
                                dropShadow: {
                                    enabled: true,
                                    top: 2,
                                    left: 0,
                                    blur: 4,
                                    opacity: 0.15
                                }
                            },
                            dataLabels: {
                                show:true,
                                name: {
                                    offsetY: -5,
                                    color: "#fff",
                                    fontSize: "13px"
                                },
                                value: {
                                    color: "#fff",
                                    fontSize: "20px",
                                    show: true
                                },
                                total: {
                                    show: true,
                                    label: "Cpu",
                                    color: "#fff",
                                }
                            }
                        }
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            shade: "dark",
                            type: "vertical",
                            gradientToColors: ["#eb3349"],
                            stops: [50, 100]
                        }
                    },
                    stroke: {
                        lineCap: "round"
                    }
                };
                var chart = new ApexCharts(document.querySelector("#cpu"), options);
                chart.render();
            },
            affiche(){
                var option =  {
                    chart: {
                        height: 350,
                        type: 'donut',
                    },
                    plotOptions: {
                        pie: {
                            expandOnClick: true,
                            donut: {
                                labels: {
                                    show: true,
                                    name: {
                                        show:true,
                                    },
                                    value: {
                                        show:true,
                                    },
                                    total:{
                                        show:true
                                    }
                                }
                            }
                        }
                    },
                    series: [this.stat.analytics[0].sessions, this.stat.analytics[1].sessions],
                    labels: ['Nouveaux Visiteurs', 'Anciens Visiteurs']
                }

                    var chart = new ApexCharts(
                        this.$el.querySelector('#chart'),
                        option
                    );
                    setTimeout(function () { chart.render(); }.bind(chart), 500)

            },
            courbe(){
                var option =  {
                    chart: {
                        height: 350,
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
                        data: this.stat.analyticsUser.visitors
                    }, {
                        name: 'Page vues',
                        data: this.stat.analyticsUser.pageViews
                    }],

                    xaxis: {
                        type: 'category',
                        categories: this.stat.analyticsUser.date,
                    },
                    tooltip: {
                        x: {
                            format: 'dd/MM/yy HH:mm'
                        },
                    }
                }
                var chart = new ApexCharts(
                    this.$el.querySelector('#courbe'),
                    option
                );
                setTimeout(function () { chart.render(); }.bind(chart), 500)

            },
        },
        mounted(){
            this.$parent.titre = "Administration"
            this.getInfo()
            this.getStat()
        }
    }
</script>
<style>
    #chart .apexcharts-tooltip{
        color: white!important;
    }
</style>