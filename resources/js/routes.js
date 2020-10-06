import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

function logout(){
    var url = '/authenticate/logout'
    axios.post(url)
        .then(res=>{
            if(res.data.code == 204 ){
                localStorage.clear()
                alert('Su session ha caducado')
                location.reload()
            }
        })
        .catch(error=>{
            console.log(error.response)
            if(error.response.status == 401){
                localStorage.clear()
                $router.push({name: 'login'})
                location.reload()
            }
    })
}

function verificarAcceso(to, from, next){
    const timeOut = 14*60*1000+30
    let authUser = JSON.parse(localStorage.getItem('authUser'))
    setTimeout(() => {
        logout()
    }, timeOut);
    if (authUser) {
        next()
    }else{
        next({name: 'login'})
    }
}

function verificarPermisions(to, from, next){
    const timeOut = 14*60*1000+30
    let authUser = JSON.parse(localStorage.getItem('authUser'))
    if (authUser.is_admin == 1) {
        next()
    }else{
        next({name: 'login'})
    }
}

export default new Router({
    routes:[
        {
            path: '/login',
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
            }
        },
        {
            path: '/usuarios',
            name: 'admin.usuarios.index',
            component: require('./components/modules/admin/users/index.vue').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next)
                verificarPermisions(to, from, next)
            }
        },
        {
            path: '/mi-perfil',
            name: 'mi-perfil',
            component: require('./components/modules/profile/profile.vue').default,
            beforeEnter: (to, from, next) => {
                verificarAcceso(to, from, next)
                verificarPermisions(to, from, next)
            }
        },
        // { path: '/cliente', component: require('./components/modulos/cliente/index.vue').default },
        // { path: '/pedido', component: require('./components/modulos/pedido/index.vue').default },
        // { path: '/permiso', component: require('./components/modulos/permiso/index.vue').default },
        // { path: '/permiso/crear', component: require('./components/modulos/permiso/create.vue').default },
        // {   path: '/permiso/editar/:id',
        //     name: 'permiso.editar',
        //     component: require('./components/modulos/permiso/edit.vue').default,
        //     props: true
        // },
        // { path: '/producto', component: require('./components/modulos/producto/index.vue').default },

        // { path: '/usuario', component: require('./components/modulos/usuario/index.vue').default },
        // { path: '/usuario/crear', component: require('./components/modulos/usuario/create.vue').default },
        // {   path: '/usuario/ver/:id',
        //     name: 'usuario.ver',
        //     component: require('./components/modulos/usuario/view.vue').default,
        //     props: true
        // },
        // {   path: '/usuario/editar/:id',
        //     name: 'usuario.editar',
        //     component: require('./components/modulos/usuario/edit.vue').default,
        //     props: true
        // },

        // { path: '/rol', component: require('./components/modulos/rol/index.vue').default },
        // { path: '/rol/crear', component: require('./components/modulos/rol/create.vue').default },
        // {   path: '/rol/editar/:id',
        //     name: 'rol.editar',
        //     component: require('./components/modulos/rol/edit.vue').default,
        //     props: true
        // },
        // { path: '/reporte', component: require('./components/modulos/reporte/index.vue').default }
    ],
    mode:'history',
    linkExactActiveClass: 'active'
})
