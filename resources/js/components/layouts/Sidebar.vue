<template>
    <ul id="slide-out" class="sidenav sidenav-fixed">
            <li><div class="user-view">
                    <div class="background">
                        <img src="#">
                    </div>
                    <div class="row bottom">
                        <div class="col left">
                            <a class="white-text" href="#"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
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
                            <i class="tiny material-icons">group</i>Administracion de Usuarios
                        </router-link>
                    </li>
                </template>
                <li><div class="divider"></div></li>
                    <li>
                        <router-link :to="{name: 'dashboard.index'}" class="nav-link active">
                            <i class="tiny material-icons">dashboard</i>Dashboard
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
        },
        data(){
            return{
                userAuth: ''
            }
        },
        methods:{
            getUserAuth(){
                this.userAuth = JSON.parse(this.auth)
            }
        }
    }
</script>
