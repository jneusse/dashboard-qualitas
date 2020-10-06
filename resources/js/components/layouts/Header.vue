<template>
        <header>
            <nav>
                <div class="navbar-fixed">
                    <a href="#" data-target="slide-out" class="sidenav-trigger hide-on-large black-text"><i class="material-icons">menu</i></a>
                    <a class="brand-logo" href="#">
                    </a>

                    <div id="navbarSupportedContent">
                        <ul class="left hide-on-med-and-down">
                        </ul>

                        <ul class="right">
                            <!-- Dropdown Structure -->
                            <ul id="dropdown1" class="dropdown-content">
                                <!-- <li><a class="black-text" href="#"><img class="circle" alt="profile" width="15%" height="15%" src="#"> Mi cuenta</a></li> -->
                                <router-link :to="{name: 'mi-perfil'}" class="nav-link active">
                                    <li><a class="black-text" href="#">Mi cuenta</a></li>
                                </router-link>

                                <li class="divider"></li>
                                <li>
                                    <a class="dropdown-item black-text" @click.prevent="logout" v-loading.fullscreen.lock="fullscreenLoading">Logout</a>

                                    <form id="logout-form" action="logout" method="POST" style="display: none;">
                                    </form>
                                </li>
                            </ul>
                            <!-- Dropdown Trigger -->
                            <li>
                                <a class="dropdown-trigger btn show-on-small" href="#!" data-target="dropdown1">
                                    Mi cuenta<i class="material-icons right">arrow_drop_down</i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
</template>

<script>
    export default {
        props:['ruta', 'auth'],
        data(){
            return{
                fullscreenLoading: false,
            }
        },
        mounted() {
            // console.log(this.authUser)
        },
        methods:{
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
