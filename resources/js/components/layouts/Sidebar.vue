<template>
    <ul id="slide-out" class="sidenav sidenav-fixed">
            <li>
                <div class="user-view">
                        <div class="background background-color">
                        </div>
                        <router-link :to="{name: 'mi-perfil'}" class="nav-link active">
                            <a href="#"><img class="circle" :src="userAuth.photo"></a>
                            <a href="#"><span class="white-text name">{{userAuth.name}}</span></a>
                            <a href="#"><span class="white-text email">{{userAuth.email}}</span></a>
                        </router-link>
                    <div class="row bottom">
                        <div class="col right">
                            <a class="white-text" href="#!" v-loading.fullscreen.lock="fullscreenLoading" @click.prevent="logout">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        <li class="no-padding">
            <ul class="collapsible">
                <template v-if="userAuth.is_admin == 1">
                    <li>
                        <router-link :to="{name: 'admin.usuarios.index'}" class="nav-link active">
                            <i class="tiny material-icons">group</i>Administración de Usuarios
                        </router-link>
                    </li>
                </template>
                <li><div class="divider"></div></li>
                    <li>
                        <router-link :to="{name: 'dashboard.index'}" class="nav-link active">
                            <i class="tiny material-icons">dashboard</i>Dashboard
                        </router-link>
                    </li>
                    <li>
                        <router-link :to="{name: 'dashboard.interaction'}" class="nav-link active">
                            <i class="tiny material-icons">assessment</i>Interacciones On-line
                        </router-link>
                    </li>
            </ul>
        </li>
        <li><div class="divider"></div></li>
    </ul>
</template>

<script>
    export default {
        props:['ruta', 'auth'],
        mounted() {
            this.getUserAuth()

            Echo.channel('channel-interactions')
                .listen('AlertInteraction', e => {
                    let interaction = e.interaction
                    const noti = this.$vs.notification({
                        duration: 'none',
                        position: 'top-right',
                        title: 'Nueva Interacción',
                        text: `<a class='message-noti' href='/interactions/${interaction.id}'>
                            Call ID: <b>${interaction.callId}</b><br>
                            Mensaje Texto: <b>${interaction.messageText}</b></a>`
                    })
                    console.log(e);
                })
        },
        data(){
            return{
                userAuth: '',
                fullscreenLoading: false
            }
        },
        created(){
            this.$root.$on('userAuth', userAuth =>{
                this.getUserAuth()
            })
        },
        methods:{
            getUserAuth(){
                this.userAuth = JSON.parse(localStorage.getItem('authUser'))
                if(this.userAuth.photo){
                    this.userAuth.photo = this.userAuth.photo
                }else{
                    this.userAuth.photo = '/images/default-user.png'
                }
                // console.log(this.userAuth.photo);
            },
            logout(){
                this.fullscreenLoading = true
                let url = '/authenticate/logout'
                console.log('logout');
                axios.post(url)
                    .then(res=>{
                        console.log(res.data)
                        if(res.data.code == 204 ){
                            localStorage.clear()
                            this.$router.push({name: 'login'})
                            location.reload()
                            // this.fullscreenLoading = false
                        }
                    })
                    .catch(error=>{
                        console.log(error.response)
                        if(error.response.status == 401){
                            this.$router.push({name: 'login'})
                            localStorage.clear()
                            location.reload()
                            // this.fullscreenLoading = false
                        }
                    })
            }
        }
    }
</script>
<style>
.message-noti{
    color: #38c172;
}
</style>
