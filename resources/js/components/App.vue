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
                var alert = setTimeout(() => {
                        Swal.fire({
                            title: 'Su sessión esta apunto de caducar',
                            timer: 5000,
                            icon: 'warning'
                        })
                        }, 14*60*1000);
                var logout = setTimeout(() => {
                    var url = '/authenticate/logout'
                    axios.post(url)
                        .then(res=>{
                            if(res.data.code == 204 ){
                                localStorage.clear()
                                location.reload()
                            }
                        })
                        .catch(error=>{
                            console.log(error.response)
                            if(error.response.status == 401){
                                localStorage.clear()
                                this.$router.push({name: 'login'})
                                location.reload()
                            }
                        })
                    }, (14*60*1000)+(30*1000));
                $('body').click(function(){
                    clearTimeout(alert)
                    clearTimeout(logout)
                    alert = setTimeout(() => {
                        Swal.fire({
                        title: 'Su sessión esta apunto de caducar',
                        timer: 5000,
                        icon: 'warning'
                    })
                    }, 14*60*1000);
                    logout = setTimeout(() => {
                        var url = '/authenticate/logout'
                        axios.post(url)
                            .then(res=>{
                                if(res.data.code == 204 ){
                                    localStorage.clear()
                                    location.reload()
                                }
                            })
                            .catch(error=>{
                                console.log(error.response)
                                if(error.response.status == 401){
                                    localStorage.clear()
                                    this.$router.push({name: 'login'})
                                    location.reload()
                                }
                            })
                    }, (14*60*1000)+(30*1000));
                })

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
