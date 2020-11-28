<template>
    <div class="container full-width">
        <div class="row">
            <div class="col s12">
                <div class="row">
                    <div class="col">
                        <h4>Dashboard</h4>
                    </div>
                    <div class="col">
                        <el-date-picker
                            v-model="range"
                            type="daterange"
                            range-separator="a"
                            :default-value="new Date()"
                            start-placeholder="Fecha inicio"
                            end-placeholder="Fecha fin"
                            @change="getInteractions(1)">
                        </el-date-picker>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 l6">
                        <div class="card" id="cardGraphMonth">
                            <div class="card-content">
                                <canvas id="pieByPeriod" width="auto" height="auto"></canvas>
                                <template v-if="showSpanNoData">
                                    <span class="black-text"><br>Sin registros</span>
                                </template>
                            </div>
                            <div class="card-action card-footer right-align">
                                <p><b>Total Interacciones {{interactions.total}} </b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 l6">
                        <div class="panel-interactions">
                            <div class="card-panel">Total interacciones CON eventos clave: <b>{{interactions.eventosClave}}</b></div>
                            <div class="card-panel">Total interacciones SIN eventos clave: <b>{{interactions.sinEventosClave}}</b></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <div class="card">
                            <div class="card-content">
                                <canvas id="lineByDays" width="auto" height="35%"></canvas>
                                <template v-if="showSpanNoData">
                                    <span class="black-text"><br>Sin registros</span>
                                </template>
                            </div>
                            <div class="card-action card-footer right-align ">
                                <p><b>Periodo del {{interactions.firstDay}} al {{interactions.lastDay}}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vs-notification__content__text"><p>Cliente 132123 Agente <a href="/interactions"> 2 </a> 123123 Mensaje Texto Choque múltiple con heridos</p></div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            range:[],
            tableLoading: false,
            fullscreenLoading: false,
            interactions: {
                'total': 0,
                'eventosClave': 0,
                'sinEventosClave': 0,
                'days': [],
                'firstDay': '',
                'lastDay': ''
            },
            showSpanNoData: false,
            modalEditName: '',
            errorEditUser: '',
            errorCreateUser: '',
            modalEdit: '',
            modalCreate: '',
            conversation: {
                client: '',
                messages: []
            },
            messagesLoading: true,
            conversationTittle: '',
        }
    },
    mounted(){
        M.AutoInit();
        const start = new Date();
        const date = start.getDate()
        start.setTime(start.getTime() - 3600 * 1000 * 24 * (date-1));
        this.range[0] = start;
        this.range[1] = new Date();
        this.getInteractions()
    },
    computed:{
    },
    methods:{
        getInteractions(page = 1){
            let url = '/interactions/getTotalInteractions'

            // Formatear startDate YYYY-MM-DD 00:00:00
            const startDate = (date) => {
                let year = date.getFullYear()
                let month = ("0" + (date.getMonth() + 1)).slice(-2)
                let day = ("0" + (date.getDate())).slice(-2)
                let startDate = `${year}-${month}-${day} 00:00:00`
                return startDate;
            }
            // Formatear endDate YYYY-MM-DD 00:00:00
            const endDate = (date) => {
                let year = date.getFullYear()
                let month = ("0" + (date.getMonth() + 1)).slice(-2)
                let day = ("0" + (date.getDate())).slice(-2)
                let endDate = `${year}-${month}-${day} 23:59:59`
                return endDate;
            }

            var params = {
                'startDate': startDate(this.range[0]),
                'endDate': endDate(this.range[1])
            }
            this.tableLoading = true
            axios.get(url, {params: params})
                .then( res => {
                    // console.log(res.data.days);
                    this.interactions.total = res.data.total
                    this.interactions.eventosClave = res.data.eventosClave
                    this.interactions.sinEventosClave = res.data.sinEventosClave
                    this.interactions.days = res.data.days
                    this.interactions.firstDay = res.data.firstDay
                    this.interactions.lastDay = res.data.lastDay
                    this.builtGraph()
                    this.tableLoading = false
                })
                .catch( err => {
                    console.log(err.response);
                    if(err.response.status == 401){
                        localStorage.clear()
                        this.$router.push({name: 'login'})
                        location.reload()
                    }
                })
        },
        builtGraph(){
            let pieByPeriod = document.getElementById('pieByPeriod')
            let lineByDays = document.getElementById('lineByDays')

            if(this.myChartPieByPeriod) this.myChartPieByPeriod.destroy()
            if(this.myChartlineByDays) this.myChartlineByDays.destroy()

            if( this.interactions.total > 0 ){

                this.showSpanNoData = false

                this.myChartPieByPeriod = new Chart(pieByPeriod, {
                    type: 'doughnut',
                    data: {
                        labels: ['Llamadas con eventos clave','Llamadas sin eventos clave'],
                        datasets: [{
                            data: [ this.interactions.eventosClave,
                                    this.interactions.sinEventosClave
                                ],
                            backgroundColor: ['#8ED9A0','#8E93D9'],
                            borderColor: 'white',
                            borderWidth: 3,
                            hoverBorderColor: ['#9DF0B1','#A1A6F5'],
                        }]
                    },
                    options: {
                        legend: false,
                        backgroundColor: 'grey'
                    }
                });

                let days = this.interactions.days
                const labels = Object.keys(days)
                const values = Object.values(days)
                let conEventos = []
                let sinEventos = []
                values.forEach(element => {
                    conEventos.push(element[0])
                    sinEventos.push(element[1])
                });

                this.myChartlineByDays = new Chart(lineByDays, {
                    type: 'line',
                    data: {
                        labels: labels, // array de ejecutivos
                        datasets: [{
                            data: sinEventos,
                            fill: false,
                            backgroundColor: '#00bb2d',
                            borderColor: '#00bb2d',
                            pointRadius: 1,
                        },{
                            data: conEventos,
                            fill: false,
                            backgroundColor:'#93D3FF',
                            borderColor: '#93D3FF',
                            pointRadius: 1,
                        }]
                    },
                    options: {
                        legend: false,
                        scales: {
                            yAxes: [{
                                stacked: false
                            }]
                        }
                    }
                });

            }else{
                this.showSpanNoData = true
            }
        },
        getConversationExcel(page = 1){
            let url = '/conversation/getConversationExcel'

            // Formatear startDate YYYY-MM-DD 00:00:00
            const startDate = (date) => {
                let year = date.getFullYear()
                let month = ("0" + (date.getMonth() + 1)).slice(-2)
                let day = ("0" + (date.getDate())).slice(-2)
                let startDate = `${year}-${month}-${day} 00:00:00`
                return startDate;
            }
            // Formatear endDate YYYY-MM-DD 00:00:00
            const endDate = (date) => {
                let year = date.getFullYear()
                let month = ("0" + (date.getMonth() + 1)).slice(-2)
                let day = ("0" + (date.getDate())).slice(-2)
                let endDate = `${year}-${month}-${day} 23:59:59`
                return endDate;
            }

            var params = {
                'startDate': startDate(this.range[0]),
                'endDate': endDate(this.range[1])
            }
            this.tableLoading = true
            axios.get(url, {
                    responseType: 'blob',
                    params: params
                })
                .then( res => {
                    var oMyBlob = new Blob([res.data], {type : 'application/vnd.ms-excel'}); // the blob
                    let url = document.createElement('a')
                    url.href = URL.createObjectURL(oMyBlob)
                    url.download = 'data.xlsx'
                    url.click()
                    this.tableLoading = false
                })
                .catch( err => {
                    console.log(err.response);
                    if(err.response.status == 401){
                        localStorage.clear()
                        this.$router.push({name: 'login'})
                        location.reload()
                    }
                })
        }
    }
}
</script>

<style>
.el-range-editor.el-input__inner{
    margin-top: 20px !important;
}
.panel-interactions{
    margin-top: 20%;
}
.card-footer{
    padding: 1px 24px !important;
}
</style>
