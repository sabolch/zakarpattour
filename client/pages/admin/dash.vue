<template>
    <v-container fluid pa-0 ma-0>
        <v-layout row wrap>

            <v-flex xs12 class="mb-1 pa-1">
                <v-card elevation="5">
                    <v-card-title class="primary white--text title pa-4">
                        Logged In to Admin Dash
                    </v-card-title>
                </v-card>
            </v-flex>

            <v-flex sm6 xs12 md3 v-for="item in panels" :key="item.icon" class="pa-1">
                <mini-statistic :item="item"></mini-statistic>
            </v-flex>


            <v-flex xs12  md6 class="pa-1">
                <v-card elevation="5">
                    <v-card-title class="green white--text title">
                        Logged In to Admin Dash
                    </v-card-title>
                    <v-card-text>
                        <LineChart :data="barChartData" :options="lineChartoptions"/>
                    </v-card-text>
                </v-card>
            </v-flex>

            <v-flex xs12 md6 class="pa-1">
                <v-card elevation="5">
                    <v-card-title class="primary white--text title">
                        Logged In to Admin Dash
                    </v-card-title>
                    <v-card-text>
                        <PieChart :data="pieData" :options="{ maintainAspectRatio: false }"/>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs12 md6 class="pa-1">
                <v-card elevation="5">
                    <v-card-title class="orange white--text">
                        Logged In to Admin Dash
                    </v-card-title>
                    <v-card-text>
                        <DoughnutChart :data="pieData" :options="{ maintainAspectRatio: false }"/>
                    </v-card-text>
                </v-card>
            </v-flex>

            <v-flex xs12 md6 class="pa-1">
                <v-card elevation="5">
                    <v-card-title class="purple white--text">
                        Logged In to Admin Dash
                    </v-card-title>
                    <v-card-text>
                        <BarChart :data="barChartData" :options="lineChartoptions"/>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import LineChart from "../../components/chartjs/line-chart";
    import PieChart from "../../components/chartjs/pie-chart";
    import DoughnutChart from "../../components/chartjs/doughnut-chart";
    import BarChart from "../../components/chartjs/bar-chart";
    import MiniStatistic from "../../components/admin/mini-statistic";

    export default {
        name: "dash",
        components: {MiniStatistic, BarChart, DoughnutChart, PieChart, LineChart},
        layout: 'admin',
        middleware: 'admin',
        async asyncData({store,$axios, redirect}) {
            try {
                $axios.setToken(store.state.admin.token,'Bearer')
                const {data} = await $axios.get('admin/statistic')
                let statistics = data.data
                return {
                    statistics:statistics,
                    barChartData: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                        datasets: [
                            {
                                label: 'Data One',
                                backgroundColor: ['rgba(230, 155, 155, 0.5)','rgba(120, 15, 155, 0.5)','rgba(30, 155, 188, 0.5)'],
                                borderColor:'rgba(230, 155, 155, 1)',
                                borderWidth:2,
                                data: [40, 25, 15, 40, 39, 65, 40],
                                pointBackgroundColor: "#dd5555"
                            }
                        ]
                    },
                    pieData :{
                        labels: ["Settlements", "Sights", "Tours"],
                        datasets: [
                            {
                                label: "Statistic",
                                backgroundColor: ["#41B883", "#E46651", "#00D8FF"],
                                data: [statistics.settlements,statistics.sights, statistics.tours]
                            }
                        ]
                    }

                }

            } catch (e) {
                redirect('/error')
            }

        },

        data() {
            return {
                compact: false,
                statistics:{},
                lineChartoptions:{
                    maintainAspectRatio: false,
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display:false
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            },
                            gridLines: {
                                display:false
                            }
                        }]
                    }
                },
                panels:[
                    {color:'green darken-2',title:'200',icon:'group_add',subTitle:'New Users'},
                    {color:'purple',title:'350',icon:'location_city',subTitle:'Settlements'},
                    {color:'blue darken-2',title:'892',icon:'location_on',subTitle:'Sights'},
                    {color:'red lighten-2',title:'152',icon:'favorite',subTitle:'In Favorites'},
                ],
                pieData:{}
            }
        },
        computed:{
            breakpoint() {
                return this.$vuetify.breakpoint.xs
            }
        },
        mounted() {
            this.panels[0].title = this.statistics.users
            this.panels[1].title = this.statistics.settlements
            this.panels[2].title = this.statistics.sights
            this.panels[3].title = this.statistics.fovorites


        }
    }
</script>

<style scoped>

</style>
