<template>
    <div class="main">
        <!--Nav Bar-->
        <Header :ruta="ruta"/>
        <!--Content-->
            <!--Sidebar-->
            <Sidebar :ruta="ruta" :auth="authUser"/>
            <main>
                <transition name="el-fade-in-linear">
                    <router-view/>
                </transition>
            </main>
            <!-- Footer -->
        <Footer/>

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

        },
        watch: {
            '$route' (to, from) {
                document.title = to.meta.title+" | Laravel" || 'Laravel'
            },
            immediate: true
        },
    }
</script>

<style>

</style>
