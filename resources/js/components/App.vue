<template>
    <div>
        <!--Nav Bar-->
        <Header :ruta="ruta"/>
        <!--Content-->
            <!--Sidebar-->
            <Sidebar :ruta="ruta" :auth="authUser"/>
        <main>
            <div class="content-wrapper">
                <transition name="slide-fade" modo="in-out">
                    <router-view/>
                </transition>
            </div>
        </main>
        <!--Footer-->
        <!-- <Footer/> -->

        <!-- Control Sidebar -->
        <!-- <aside class="control-sidebar control-sidebar-dark"> -->
            <!-- Control sidebar content goes here -->
        <!-- </aside> -->
        <!-- /.control-sidebar -->

    </div>
</template>

<script>
    import Header from './layouts/Header'
    import Sidebar from './layouts/Sidebar'
    import Footer from './layouts/Footer'
    export default {
        props:['ruta', 'auth'],
        components: {Header, Sidebar, Footer},
        data(){
            return{
                authUser : this.auth
            }
        },
        mounted(){
            // console.log(this.auth);
            // EventBus.$on('verifyAuthenticatedUser', data => {
            //     this.authUser = data;
            // })
        }
    }
</script>

<style>
    header, main, footer {
      padding-left: 300px;
    }

    @media only screen and (max-width : 992px) {
      header, main, footer {
        padding-left: 0;
      }
    }
    .page-footer {
        position: absolute;
        bottom: 0;
        width: 100%;
    }
</style>
