<template>
    <div class="container full-width">
        <div class="row main-container">
            <div class="col s12">
                <div class="row">
                    <div class="col">
                        <h5>Mi Perfil</h5>
                    </div>
                </div>
                <div class="row margin-1">
                    <div class="col s12 l3">
                        <div class="card card-min">
                            <div class="card-image">
                                <img :src="profilePhoto" :alt="userAuth.name" @click.prevent="selectFile">
                                <span class="card-title">{{ userAuth.name }}</span>
                                <input hidden id="inputFile" type="file" @change="getFile">
                            </div>
                        </div>
                        <div>
                            <button class="btn" @click.prevent="selectFile">Cambiar</button>
                        </div>
                    </div>
                    <div class="col s12 l9">
                        <form>
                            <div class="row">
                                <div class="input-field col s12 l7">
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
                                <div class="input-field col s12 l7">
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

                            <div class="row">
                                <div class="input-field col s12 l7">
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
                                <div class="input-field col s12 l7">
                                    <i class="material-icons prefix">mode_edit</i>
                                    <input id="password-confirm" type="password" v-model="formEditUser.passwordConfirm" :disabled="(formEditUser.password.length > 7) ? false: true"  placeholder="********" autocomplete="on">
                                    <template v-if="formEditUser.passwordConfirm && formEditUser.passwordConfirm.length > 7 && formEditUser.password !== formEditUser.passwordConfirm">
                                        <span class="new badge red" data-badge-caption="Las contraseñas no coinciden" role="alert"></span>
                                    </template>
                                    <label for="password-confirm" class="active">Confirmar Contraseña</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12 l7">
                                    <div class="col s6">
                                        <div class="input-field left">
                                            <a class="btn grey" @click.prevent="resetForm">Cancelar</a>
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
                        </form>
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
            formEditUser:{
                name: '',
                email: '',
                password: '',
                photo: '',
                passwordConfirm: ''
            },
            userAuth: '',
            profilePhoto: '',
            form: new FormData,
            errorEditUser: '',
            fullscreenLoading: false
        }
    },
    mounted(){
        this.getUserAuth()

        M.AutoInit();

    },
    computed:{
        validEmail() {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.formEditUser.email)
        },
    },
    methods:{
        getUserAuth(){
            this.userAuth = JSON.parse(localStorage.getItem('authUser'))
            if(this.userAuth.photo){
                this.profilePhoto = this.userAuth.photo
            }else{
                this.profilePhoto = '/storage/profile-images/default-user.png'
            }
            this.formEditUser.name = this.userAuth.name
            this.formEditUser.email = this.userAuth.email
        },
        getFile(e){
            this.formEditUser.photo =  e.target.files[0]
            this.saveFile()
        },
        saveFile(){
            this.form.append('file', this.formEditUser.photo)
            const config = { headers: {'Content-Type': 'Multipart/from-data'}}
            let url = '/profile/savefile'
            if(this.validateFile(this.formEditUser.photo)){

                axios.post(url, this.form, config)
                    .then( res => {
                        // console.log(res);
                        if(res.data.save == 'OK'){
                            localStorage.setItem('authUser', JSON.stringify(res.data.user))
                            this.getUserAuth()
                            this.$root.$emit('userAuth', res.data.user)
                            Swal.fire({
                                title: 'Imagen guardada exitosamente',
                                timer: 1500,
                                icon: 'success'
                            })
                        }
                    })
                    .catch( err => {
                        if(err.response.status == 401){
                            localStorage.clear()
                            this.$router.push({name: 'login'})
                            location.reload()
                        }
                    })
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Formato inválido',
                    text: 'Por favor elige un archivo en formato jpg, gif o png.'
                    // footer: '<a href>Why do I have this issue?</a>'
                })
            }

        },
        selectFile(){
            $('#inputFile').click();
        },
        validateFile(file){
            let splitFile = file.name.split('.')
            let fileExt= splitFile[splitFile.length-1]
            if(fileExt == 'jpg' || fileExt == 'png' || fileExt == 'jpeg'|| fileExt == 'gif'){
                return true
            }else{
                return false
            }
        },
        setEditUser(){
            this.fullscreenLoading = true
            let url = '/admin/users/edit'
            let params = {
                'id': this.userAuth.id,
                'name': this.formEditUser.name,
                'email': this.formEditUser.email,
                'is_admin': this.userAuth.is_admin,
                'password': this.formEditUser.password,
                'password-confirm': this.formEditUser.passwordConfirm,
            }
            axios.post(url, params)
                .then( res => {
                    this.fullscreenLoading = false
                    if(res.data.save == 'OK'){
                        Swal.fire({
                            title: 'Guardado exitosamente',
                            timer: 1500,
                            icon: 'success'
                        })
                        localStorage.setItem('authUser', JSON.stringify(res.data.user))
                        this.getUserAuth()
                        this.$root.$emit('userAuth', res.data.user)
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
        resetForm(){
            this.formEditUser.name = this.userAuth.name
            this.formEditUser.email = this.userAuth.email
        }
    }
}
</script>

<style>
.card-min{
    width: 250px !important;
}
</style>
