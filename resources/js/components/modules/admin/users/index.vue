<template>
    <div class="container full-width">
        <div class="row">
            <div class="col s12">
                <div class="row">
                    <div class="col">
                        <h4>Usuarios</h4>
                    </div>
                    <div class="col right">
                        <h4>
                            <el-tooltip class="item modal-trigger" data-target="modalCreate" effect="dark" content="Agregar usuario" placement="bottom-end">
                                <a class="btn"><i class="material-icons">add_circle</i></a>
                            </el-tooltip>
                            <el-tooltip class="item" effect="dark" content="Buscar" placement="bottom-end">
                                <a class="btn" @click.prevent="showSearch"><i class="material-icons">search</i></a>
                            </el-tooltip>

                        </h4>
                    </div>
                </div>
                <div class="card">
                    <div class="pagination">
                        <template v-if="pagination.total > pagination.per_page">
                            <ul class="pagination">
                                <li :class="[(pagination.current_page == 1) ? 'disabled' : '']"><a href="!#" @click.prevent="getUsers(1)"><i class="material-icons">first_page</i></a></li>
                                <li :class="[(pagination.current_page == 1) ? 'disabled' : '']"><a href="!#" @click.prevent="getUsers(pagination.current_page-1)"><i class="material-icons">chevron_left</i></a></li>
                                <li v-for="(item, index) in pagesNumber" :key="index" class="hide-on-med-and-down" :class="[(pagination.current_page == item) ? 'active disable' : '']">
                                    <a href="!#" @click.prevent="getUsers(item)">{{item}}</a>
                                </li>
                                <li :class="[(pagination.current_page >= pagination.last_page) ? 'disabled' : '']"> <a href="!#" @click.prevent="getUsers(pagination.current_page+1)"><i class="material-icons">chevron_right</i></a></li>
                                <li :class="[(pagination.current_page >= pagination.last_page) ? 'disabled' : '']"> <a href="!#" @click.prevent="getUsers(pagination.last_page)"><i class="material-icons">last_page</i></a></li>
                            </ul>
                        </template>
                    </div>
                    <el-collapse-transition>
                        <div class="row row-search" v-show="showSearchInput">
                            <div class="input-field col s12 transition-box">
                                <input id="searchInput" v-model="userSearch" type="text" placeholder="Buscar . . . " v-on:keyup="getUsers(1)">
                            </div>
                        </div>
                    </el-collapse-transition>
                    <div class="overflow-x">
                        <table class="highlight centered" v-loading="tableLoading">
                            <thead class="head-centered">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Tipo</th>
                                    <th>Status</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <template v-if="pagination.data.length">
                                <tbody>
                                    <tr v-for="(item, index) in pagination.data" :key="index">
                                        <td v-text="item.id"></td>
                                        <td v-text="item.name"></td>
                                        <td v-text="item.email"></td>
                                        <td v-text="item.userType"></td>
                                        <td v-text="item.status" :class="[item.status == 'Online' ? 'green-text' : 'red-text']"></td>
                                        <td>
                                            <vs-tooltip>
                                                <el-button type="primary"  class="modal-trigger" data-target="modalEdit" :plain="true" size="mini" @click.prevent="showEditUser(item)">
                                                    <i class="tiny material-icons">edit</i>
                                                </el-button>
                                                <template #tooltip>
                                                    Editar
                                                </template>
                                            </vs-tooltip>
                                            <vs-tooltip>
                                                <el-button type="warning" :plain="true" size="mini" @click="deleteUser(item.id)">
                                                    <i class="tiny material-icons">delete</i>
                                                </el-button>
                                                <template #tooltip>
                                                    Eliminar
                                                </template>
                                            </vs-tooltip>
                                        </td>
                                    </tr>
                                </tbody>
                            </template>
                            <template v-else>
                                <div class="center-align">
                                    <h6>No se encontraron usuarios</h6>
                                </div>
                            </template>
                        </table>
                    </div>


                </div>
            </div>
        </div>
        <!-- MODAL EDITAR -->
        <div class="modal modal-edit-user" id="modalEdit">
            <div class="modal-content">
                <h5>Editar Usuario: {{modalEditName}}</h5>
                <form autocomplete="on">
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <i class="material-icons prefix">account_circle</i>
                                <input id="name" type="text" v-model="formEditUser.name" required>
                                <label for="name" class="active">Nombre
                                    <span class="new badge red" data-badge-caption="" role="alert" style="display: none"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <i class="material-icons prefix">email</i>
                                <input id="email" type="email" v-model="formEditUser.email" required v-on:keyup="errorEditUser = ''">
                                <label for="email" class="active">Correo electrónico
                                    <template v-if="!validEmail && formEditUser.email !== ''">
                                        <span class="new badge red" data-badge-caption="" role="alert">Correo electrónico inválido</span>
                                    </template>
                                    <template v-if="errorEditUser">
                                        <span class="new badge red" data-badge-caption="" role="alert">{{errorEditUser}}</span>
                                    </template>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <i class="material-icons prefix">mode_edit</i>
                                <input id="password" type="password" v-model="formEditUser.password" placeholder="********" autocomplete="on">
                                <template v-if="formEditUser.password && formEditUser.password.length < 8">
                                    <span class="new badge red" data-badge-caption="Contraseña debe ser al menos 8 caracteres" role="alert"></span>
                                </template>
                                <label for="password" class="active">Contraseña, al menos 8 caracteres
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <i class="material-icons prefix">mode_edit</i>
                                <input id="password-confirm" type="password" v-model="formEditUser.passwordConfirm" :disabled="(formEditUser.password.length > 7) ? false: true"  placeholder="********" autocomplete="on">
                                <template v-if="formEditUser.passwordConfirm && formEditUser.passwordConfirm.length > 7 && formEditUser.password !== formEditUser.passwordConfirm">
                                    <span class="new badge red" data-badge-caption="Las contraseñas no coinciden" role="alert"></span>
                                </template>
                                <label for="password-confirm" class="active">Confirmar Contraseña</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s3 offset-s2">
                            <div class="input-field">
                                <p>
                                    <label for="is_admin">
                                        <input id="is_admin" v-model="formEditUser.is_admin" :checked="formEditUser.is_admin ? true : false" class="checkbox-orange" type="checkbox"/>
                                        <span>Administrador</span>
                                    </label>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col s6">
                        <div class="input-field left">
                            <a class="btn grey modal-close">Cancelar</a>
                        </div>
                    </div>
                    <div class="col s6">
                        <div class="input-field right">
                        <button class="btn waves-effect" v-loading.fullscreen.lock="fullscreenLoading"
                            :class="(!this.validEmail || !this.formEditUser.name || this.formEditUser.password != this.formEditUser.passwordConfirm) ? 'disabled':''" @click.prevent="setEditUser">
                                Guardar
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                <!-- MODAL CREATE -->
        <div class="modal modal-edit-user" id="modalCreate">
            <div class="modal-content">
                <h5>Crear Usuario</h5>
                <form autocomplete="on">
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <i class="material-icons prefix">account_circle</i>
                                <input id="formCreate_name" type="text" v-model="formCreateUser.name" required>
                                <label for="formCreate_name" class="active">Nombre
                                    <span class="new badge red" data-badge-caption="" role="alert" style="display: none"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <i class="material-icons prefix">email</i>
                                <input id="formCreate_email" type="email" v-model="formCreateUser.email" required v-on:keyup="errorCreateUser = ''">
                                <label for="formCreate_email" class="active">Correo electrónico
                                    <template v-if="!validEmailCreate && formCreateUser.email !== ''">
                                        <span class="new badge red" data-badge-caption="" role="alert">Correo electrónico inválido</span>
                                    </template>
                                    <template v-if="errorCreateUser">
                                        <span class="new badge red" data-badge-caption="" role="alert">{{errorCreateUser}}</span>
                                    </template>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <i class="material-icons prefix">mode_edit</i>
                                <input id="formCreate_password" type="password" v-model="formCreateUser.password" placeholder="********" autocomplete="on">
                                <template v-if="formCreateUser.password && formCreateUser.password.length < 8">
                                    <span class="new badge red" data-badge-caption="Contraseña debe ser al menos 8 caracteres" role="alert"></span>
                                </template>
                                <label for="formCreate_password" class="active">Contraseña, al menos 8 caracteres
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <i class="material-icons prefix">mode_edit</i>
                                <input id="formCreateUser_password-confirm" type="password" v-model="formCreateUser.passwordConfirm" :disabled="(formCreateUser.password.length > 7) ? false: true"  placeholder="********" autocomplete="on">
                                <template v-if="formCreateUser.passwordConfirm && formCreateUser.passwordConfirm.length > 7 && formCreateUser.password !== formCreateUser.passwordConfirm">
                                    <span class="new badge red" data-badge-caption="Las contraseñas no coinciden" role="alert"></span>
                                </template>
                                <label for="formCreateUser_password-confirm" class="active">Confirmar Contraseña</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s3 offset-s2">
                            <div class="input-field">
                                <p>
                                    <label for="formCreateUser_is_admin">
                                        <input id="formCreateUser_is_admin" v-model="formCreateUser.is_admin" :checked="formCreateUser.is_admin ? true : false" class="checkbox-orange" type="checkbox"/>
                                        <span>Administrador</span>
                                    </label>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col s6">
                        <div class="input-field left">
                            <a class="btn grey modal-close" @click.prevent="closeModalCreate">Cancelar</a>
                        </div>
                    </div>
                    <div class="col s6">
                        <div class="input-field right">
                            <button class="btn waves-effect" v-loading.fullscreen.lock="fullscreenLoading"
                                :class="(   !this.validEmailCreate ||
                                            !this.formCreateUser.name ||
                                            !this.formCreateUser.password ||
                                            this.formCreateUser.password != this.formCreateUser.passwordConfirm) ? 'disabled':''" @click.prevent="setCreateUser">
                                    Guardar
                            </button>
                        </div>
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
            userSearch: '',
            showSearchInput: false,
            formEditUser:{
                id: '',
                name: '',
                email: '',
                is_admin: false,
                password: '',
                passwordConfirm: ''
            },
            modalEditName: '',
            errorEditUser: '',
            formCreateUser:{
                name: '',
                email: '',
                is_admin: false,
                password: '',
                passwordConfirm: '',
            },
            errorCreateUser: '',
            modalEdit: '',
            modalCreate: ''
        }
    },
    mounted(){
        this.getUsers()
        M.AutoInit();
    },
    computed:{
        validEmail() {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.formEditUser.email)
        },
        validEmailCreate() {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.formCreateUser.email)
        },
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
            setTimeout(() => {
                document.getElementById('searchInput').focus()
            }, 500);
        },
        getUsers(page = 1){
            let url = '/admin/users'
            let params = {
                'page': page
            }
            if(this.userSearch){
                url = '/admin/users/search'
                params = {
                    'page': page,
                    'text': this.userSearch
                }
            }
            if(page > 0 && page <= this.pagination.last_page){
                this.tableLoading = true
                axios.get(url, {params: params})
                    .then( res => {
                        this.pagination = res.data
                        this.tableLoading = false
                        this.pagination.data.forEach(element => {
                            if(element.is_admin == 1){
                                element.userType = 'Administrador'
                            }else{
                                element.userType = 'Usuario'
                            }
                        });
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
        showEditUser(item){
            this.formEditUser.id = item.id
            this.modalEditName = item.name
            this.formEditUser.name = item.name
            this.formEditUser.email = item.email
            this.formEditUser.is_admin = item.is_admin
            this.formEditUser.password = ''
            this.formEditUser.passwordConfirm = ''
        },
        deleteUser(id) {
            let me = this
            this.$confirm('¿Desea eliminar permanentemente este registro?', 'Atención', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(() => {
                let url = '/admin/users/delete'
                axios.post(url, {'id': id})
                    .then( res => {
                        if(res.data.status == 'OK'){
                                Swal.fire({
                                    title: 'Usuario eliminado exitosamente',
                                    timer: 1500,
                                    icon: 'success'
                                })
                            this.getUsers(this.pagination.current_page)
                        }else{
                            me.$message({
                                type: 'success',
                                message: 'Error al eliminar usuario. Recargue la página'
                            });
                        }
                    })
                    .catch( err => {
                        if(err.response.status == 401){
                            localStorage.clear()
                            this.$router.push({name: 'login'})
                            location.reload()
                        }
                    })
            })
        },
        setEditUser(){
            this.modalEdit = M.Modal.getInstance($('#modalEdit'));
            this.fullscreenLoading = true
            let url = '/admin/users/edit'
            let params = {
                'id': this.formEditUser.id,
                'name': this.formEditUser.name,
                'email': this.formEditUser.email,
                'is_admin': this.formEditUser.is_admin,
                'password': this.formEditUser.password,
                'password-confirm': this.formEditUser.passwordConfirm,
            }
            axios.post(url, params)
                .then( res => {
                    this.fullscreenLoading = false
                    if(res.data.save == 'OK'){
                        this.modalEdit.close()
                        Swal.fire({
                            title: 'Usuario guardado exitosamente',
                            // timer: 1500,
                            icon: 'success'
                        })
                        this.getUsers(this.pagination.current_page)
                    }else if(res.data.save == 'error'){
                        this.errorEditUser = res.data.errors.email[0]
                    }
                })
                .catch( err => {
                    if(err.response.status == 401){
                        localStorage.clear()
                        this.$router.push({name: 'login'})
                        location.reload()
                    }
                })

        },
        setCreateUser(){
            let url = '/admin/users/create'
            let params = {
                'name': this.formCreateUser.name,
                'email': this.formCreateUser.email,
                'is_admin': this.formCreateUser.is_admin,
                'password': this.formCreateUser.password,
                'password_confirmation': this.formCreateUser.passwordConfirm
            }
            // console.log(params);
            axios.post(url, params)
                .then( res=> {
                    if(res.data.save == 'OK'){
                        this.closeModalCreate()
                        Swal.fire({
                            title: 'Usuario creado exitosamente',
                            timer: 1500,
                            icon: 'success'
                        })
                        this.getUsers(this.pagination.current_page)
                    }else if(res.data.save == 'error'){
                        this.errorCreateUser = res.data.errors.email[0]
                    }
                })
                .catch( err => {
                    if(err.response.status == 401){
                        localStorage.clear()
                        this.$router.push({name: 'login'})
                        location.reload()
                    }
                })
        },
        closeModalCreate(){
            this.modalCreate = M.Modal.getInstance($('#modalCreate'));
            this.modalCreate.close()
            this.formCreateUser.name = ''
            this.formCreateUser.email = ''
            this.formCreateUser.password = ''
            this.formCreateUser.passwordConfirm = ''
            this.formCreateUser.is_admin = false
        }
    }
}
</script>

<style>
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
</style>
