<template>
    <v-container fluid>
        <v-layout row wrap>
            <v-flex xs12>
                Logged In to Admin Dash

            </v-flex>
            <v-flex xs12>
                <LineChart :data="barChartData" :options="{ maintainAspectRatio: false }" />
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import LineChart from "../../components/line-chart";
    export default {
        name: "dash",
        components: {LineChart},
        layout:'admin',
        middleware: 'admin',
        async asyncData({ env,axios }) {
            return {
                barChartData: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                    datasets: [
                        {
                            label: 'Data One',
                            backgroundColor: '#f87979',
                            data: [40, 39, 10, 40, 39, 80, 40]
                        },
                        {
                            label: 'Data Two',
                            backgroundColor: '#f8ff79',
                            data: [21, 55, 10, 78, 53, 83, 41]
                        }
                    ]
                }
            }
        }
    }
</script>

<style scoped>

</style>
