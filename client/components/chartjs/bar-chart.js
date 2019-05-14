import { Bar } from 'vue-chartjs'

export default {
    name:'bar-chart',
    extends: Bar,
    props: ['data', 'options'],
    mounted() {
        this.renderChart(this.data, this.options)
    }
}
