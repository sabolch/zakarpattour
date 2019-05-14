import { Pie } from 'vue-chartjs'

export default {
    name:'pie-chart',
    extends: Pie,
    props: ['data', 'options'],
    mounted() {
        this.renderChart(this.data, this.options)
    }
}
