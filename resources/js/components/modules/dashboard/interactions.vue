<template>
    <div class="container full-width">
        <div class="row">
            <div class="col s12">
                <div class="row">
                    <div class="col">
                        <h4>Interacciones</h4>
                    </div>
                    <div class="col">
                        <div class="block">
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
                    <div class="col right">
                        <h4>
                            <el-tooltip class="item" effect="dark" content="Buscar por número de cliente" placement="bottom-end">
                                <a class="btn" @click.prevent="showSearch"><i class="material-icons">search</i></a>
                            </el-tooltip>
                        </h4>

                    </div>

                </div>
                <div class="card">
                    <div class="row">
                      <div class="col right">
                          <template v-if="pagination.total > pagination.per_page">
                              <ul class="pagination">
                                  <li :class="[(pagination.current_page == 1) ? 'disabled' : '']"><a href="!#" @click.prevent="getInteractions(1)"><i class="material-icons">first_page</i></a></li>
                                  <li :class="[(pagination.current_page == 1) ? 'disabled' : '']"><a href="!#" @click.prevent="getInteractions(pagination.current_page-1)"><i class="material-icons">chevron_left</i></a></li>
                                  <li v-for="(item, index) in pagesNumber" :key="index" class="hide-on-med-and-down" :class="[(pagination.current_page == item) ? 'active disable' : '']">
                                      <a href="!#" @click.prevent="getInteractions(item)">{{item}}</a>
                                  </li>
                                  <li :class="[(pagination.current_page >= pagination.last_page) ? 'disabled' : '']"> <a href="!#" @click.prevent="getInteractions(pagination.current_page+1)"><i class="material-icons">chevron_right</i></a></li>
                                  <li :class="[(pagination.current_page >= pagination.last_page) ? 'disabled' : '']"> <a href="!#" @click.prevent="getInteractions(pagination.last_page)"><i class="material-icons">last_page</i></a></li>
                              </ul>
                          </template>
                      </div>
                      <div class="col right">
                          <h6>Total llamadas encontradas <b>{{pagination.total}}</b> <button class="btn btn-small tooltipped btn-excel" data-position="top" data-tooltip="Descargar Excel" @click="getConversationExcel" :disabled="(pagination.total > 0) ? false : true"><i class="tiny material-icons">insert_drive_file</i></button></h6>
                      </div>
                    </div>
                    <el-collapse-transition>
                        <div class="row row-search" v-show="showSearchInput">
                            <div class="input-field col s12 transition-box">
                                <input id="searchInput" v-model="interactionSearch" type="text" placeholder="Buscar por número de cliente . . . " v-on:keyup="getInteractions(1)">
                            </div>
                        </div>
                    </el-collapse-transition>
                    <div class="overflow-x">
                        <table class="highlight centered" v-loading="tableLoading">
                            <thead class="head-centered">
                                <tr>
                                    <th>ID de Agente</th>
                                    <th>ID de Llamada</th>
                                    <th>DNIS de Llamada</th>
                                    <th>Status</th>
                                    <th>Tipo de mensaje</th>
                                    <th>Texto</th>
                                    <th>Creación</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <template v-if="pagination.data.length">
                                <tbody>
                                    <tr v-for="(item, index) in pagination.data" :key="index" :class="item.flag_alert_indicator ? 'green-text' : ''">
                                        <td v-text="item.agentId"></td>
                                        <td v-text="item.callId"></td>
                                        <td v-text="item.dnis_call"></td>
                                        <td v-text="item.callStatus"></td>
                                        <td v-text="item.messageType"></td>
                                        <td v-text="item.messageText"></td>
                                        <td v-text="item.createdAt"></td>
                                        <td>
                                            <vs-tooltip>
                                                <el-button type="info" class="modal-trigger" data-target="modalShowInteraction" :plain="true" size="mini" @click.prevent="getDetailInteraction(item.id)">
                                                    <i class="tiny material-icons">dehaze</i>
                                                </el-button>
                                                <template #tooltip>
                                                    Detalle
                                                </template>
                                            </vs-tooltip>
                                        </td>
                                    </tr>
                                </tbody>
                            </template>
                        </table>
                        <template v-if="!pagination.data.length">
                            <div class="center-align">
                                <h6>No hay llamadas</h6>
                            </div>
                        </template>
                    </div>


                </div>
            </div>
        </div>
        <!-- MODAL DETAILS -->
        <div class="modal modal-show-interaction" id="modalShowInteraction">
            <div class="modal-title"><h6>Interacción ID {{interaction.id}}<i class="modal-close tiny material-icons right">close</i></h6></div>
            <div class="modal-content" v-loading="messagesLoading">
                <div class="row interaction-details">
                    <div class="col s12 l6">
                        ID de Llamada:
                    </div>
                    <div class="col s12 l6 interaction-details__detail">
                        {{interaction.callId}}
                    </div>
                </div>
                <div class="row interaction-details">
                    <div class="col s12 l6">
                        ID de Agente:
                    </div>
                    <div class="col s12 l6 interaction-details__detail">
                        {{interaction.agentId}}
                    </div>
                </div>
                <div class="row interaction-details">
                    <div class="col s12 l6">
                        ANIS:
                    </div>
                    <div class="col s12 l6 interaction-details__detail">
                        {{interaction.ani_call}}
                    </div>
                </div>
                <div class="row interaction-details">
                    <div class="col s12 l6">
                        DNIS:
                    </div>
                    <div class="col s12 l6 interaction-details__detail">
                        {{interaction.dnis_call}}
                    </div>
                </div>
                <div class="row interaction-details">
                    <div class="col s12 l6">
                        Status de Llamada:
                    </div>
                    <div class="col s12 l6 interaction-details__detail">
                        {{interaction.callStatus}}
                    </div>
                </div>
                <div class="row interaction-details">
                    <div class="col s12 l6">
                        Tipo de mensaje:
                    </div>
                    <div class="col s12 l6 interaction-details__detail">
                        {{interaction.messageType}}
                    </div>
                </div>
                <div class="row interaction-details">
                    <div class="col s12 l6">
                        Texto de mensaje:
                    </div>
                    <div class="col s12 l6 interaction-details__detail">
                        {{interaction.messageText}}
                    </div>
                </div>
                <div class="row interaction-details">
                    <div class="col s12 l6">
                        Evento Clave:
                    </div>
                    <div class="col s12 l6 interaction-details__detail">
                        <template v-if="interaction.flag_alert_indicator">
                            <span class="new badge green left" data-badge-caption="SI"></span>
                        </template>
                        <template v-else>
                            <span class="new badge red left" data-badge-caption="NO"></span>
                        </template>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
export default {
    data(){
        return{
            range:[],
            tableLoading: false,
            fullscreenLoading: false,
            pagination: {
                'current_page': 0,
                'from': 0,
                'to': 0,
                'total': 0,
                'per_page': 0,
                'last_page': 1,
                'data': [],
                'first_page_url': '',
                'last_page_url': '',
                'prev_page_url': '',
                'next_page_url': '',
                'path': '',
            },
            showSearchInput: false,
            interaction: {
                id: '',
                callId: '',
                agentId: '',
                ani_call: '',
                dnis_call: '',
                callStatus: '',
                messageText: '',
                messageType: '',
                flag_alert_indicator: '',
                createdAt: '',
            },
            interactionSearch: !this.$attrs.id ? '' : this.$attrs.id,
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
        this.interactionSearch = !this.$attrs.id ? '' : this.$attrs.id
        this.getInteractions(1)
    },
    created(){
        this.$root.$on('idInteraction', id =>{
                this.interactionSearch = id
                this.getInteractions(1)
            })
    },
    computed:{
        isActived: function(){
            return this.pagination.current_page
        },
        pagesNumber: function(){
            if(!this.pagination.to){
                return []
            }

            var from = this.pagination.current_page - 3
            if(from < 1) from = 1

            var  to = from + (3 * 2)
            if(to >= this.pagination.last_page) to = this.pagination.last_page

            var pageArray = []
            while(from <= to){
                pageArray.push(from)
                from++
            }
            return pageArray
        }
    },
    methods:{
        showSearch(){
            this.showSearchInput = !this.showSearchInput
            this.interactionSearch = ''
            setTimeout(() => {
                document.getElementById('searchInput').focus()
            }, 500);
        },
        getInteractions(page = 1){
            let url = '/interactions/getInteractions'

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
                'endDate': endDate(this.range[1]),
                'page': page
            }
            if(this.interactionSearch != ''){
              params = {
                  'startDate': startDate(this.range[0]),
                  'endDate': endDate(this.range[1]),
                  'page': page,
                  'search': this.interactionSearch
              }
            }
            if(page > 0 && page <= this.pagination.last_page){
                this.tableLoading = true
                axios.get(url, {params: params})
                    .then( res => {
                        // console.log(res.data);
                        this.pagination = res.data
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
            }else{
                console.log('err page');
                return
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
        },
        getDetailInteraction(item){
            let url = '/interactions/getDetailInteraction'
            let params = {
                'id': item
            }
            axios.get(url, {params: params})
                    .then( res => {
                        console.log(res.data);
                        this.interaction.id = res.data.id
                        this.interaction.callId = res.data.callId
                        this.interaction.agentId = res.data.agentId
                        this.interaction.ani_call = res.data.ani_call
                        this.interaction.dnis_call = res.data.dnis_call
                        this.interaction.callStatus = res.data.callStatus
                        this.interaction.messageText = res.data.messageText
                        this.interaction.messageType = res.data.messageType
                        this.interaction.flag_alert_indicator = res.data.flag_alert_indicator
                        this.interaction.createdAt = res.data.createdAt
                        this.messagesLoading = false
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
span.badge {
    margin-left: 0px !important;
}
.close-modal{
    margin: 0px !important;
    padding: 0px !important;
}
.modal-show-interaction{
    width: 450px;
    height: auto;
    padding: 10px !important;
}
.modal-title{
    padding: 0px 10px 10px 16px;
}
.interaction-details{
    margin: 12px 12px 12px 16px !important;
}
.interaction-details__detail{
    color: black;
    font-weight: bold;
}
.modal-content {
    padding: 10px;
}
.vs-tooltip-content{
    display: inline !important;
    max-height: 47px !important;
}
.overflow-x{
    overflow-x: auto !important;
}
.row-search{
    padding: 0px 6% 0 6% !important;
}
.btn-excel{
    margin-left: 32px !important;
}
.el-range-editor.el-input__inner{
    margin-top: 20px !important;
}
</style>
