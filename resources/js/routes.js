import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

function logout(){
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
}

function verificarAcceso(to, from, next){
    let authUser = JSON.parse(localStorage.getItem('authUser'))
    if (authUser) {
        next()
    }else{
        next({name: 'login'})
    }
}

function verificarPermisions(to, from, next){
    let authUser = JSON.parse(localStorage.getItem('authUser'))
    if (authUser.is_admin == 1) {
        next()
    }else{
        next({name: 'dashboard.index'})
    }
}


export default new Router({
    routes:[
        {
            path: '/',
            name: 'login',
            component: require('./components/modules/authenticate/login.vue').default,
            beforeEnter: (to,from, next) => {
                let authUser = JSON.parse(localStorage.getItem('authUser'))
                if (authUser) {
                    next({name: 'dashboard.index'})
                }else{
                    next()
                }
            }
        },
        {
            path: '/home',
            name: 'dashboard.index',
            component: require('./components/modules/dashboard/index.vue').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next)
            },
            meta: {
                auth: true,
                title: 'Dashboard'
            }
        },
        {
            path: '/usuarios',
            name: 'admin.usuarios.index',
            component: require('./components/modules/admin/users/index.vue').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next)
                verificarPermisions(to, from, next)
            },
            meta: {
                auth: true,
                title: 'Usuarios'
            }
        },
        {
            path: '/mi-perfil',
            name: 'mi-perfil',
            component: require('./components/modules/profile/profile.vue').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next)
                verificarPermisions(to, from, next)
            },
            meta: {
                auth: true,
                title: 'Mi Perfil'
            }
        }
    ],
    mode:'history',
    linkExactActiveClass: 'active'
})
