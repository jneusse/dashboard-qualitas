<template>
    <div class="row">
        <div class="card z-depth-1 form-login">
            <div class="card-panel">
                <span class="card-title">Inicia sesión</span>
            </div>
            <div class="card-content">
                <form>
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field ">
                                <i class="material-icons prefix">email</i>
                                <input id="email" type="email" v-model="formLogin.email" required autocomplete="email" autofocus>
                                    <template v-if="!validEmail && formLogin.email != ''">
                                        <span class="new badge red" role="alert" data-badge-caption="">Correo electrónico inválido</span>
                                    </template>
                                <label for="email">Correo electrónico</label>
                                <span class="new badge red" role="alert" data-badge-caption="" v-if="spanMessage.length">{{ spanMessage }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field ">
                                <i class="material-icons prefix">mode_edit</i>
                                <input id="password" type="password" v-model="formLogin.password" required autocomplete="current-password">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                    <span class="red-text text-darken-2" role="alert" hidden></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                    <label>
                                        <input class="filled-in" type="checkbox" name="remember" id="remember">
                                        <span>Recordarme</span>
                                    </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div class="input-field right">
                                <button type="submit" class="waves-effect waves-light btn" @click.prevent="validateLogin" v-loading.fullscreen.lock="fullscreenLoading">
                                    Sign in
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            formLogin:{
                email: '',
                password: ''
            },
            fullscreenLoading: false,
            spanMessage: ''
        }
    },
    computed:{
        validEmail() {
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.formLogin.email)
        }
    },
    methods:{
        login(){
            let params = {
                'email': this.formLogin.email,
                'password': this.formLogin.password
            }
            this.fullscreenLoading= true
            axios.post('/authenticate/login', params)
                .then(res=>{
                    if(res.data.user){
                        // console.log(res.data.user)
                        localStorage.setItem('authUser', JSON.stringify(res.data.user))
                        location.reload()
                        // setTimeout(() => {
                        //     location.reload()
                        //     this.fullscreenLoading= false
                        // }, 1000);
                    }else if(res.data.status){
                    this.fullscreenLoading= false
                        console.log(res.data)
                        this.spanMessage = res.data.status
                    }else{
                        location.reload()
                    }

                })
                .catch((err)=>{
                    console.log(err.response.status);
                    this.fullscreenLoading = false
                    if(err.response.data.errors.email[0]){
                        console.log('email');
                        this.spanMessage = err.response.data.errors.email[0]
                    }else if(err.response.status == 419){
                        console.log(err.response.status, 419);
                        alert('Su session a expirado. La página será recargada')
                        location.reload()
                    }
                })
        },
        validateLogin(){
            this.spanMessage = ''
            if(this.validEmail && this.formLogin.password){
                this.login()
            }else{
                console.log('error');
            }
        }
    }
}
</script>
<style>
    span{
        height: auto !important;
    }
</style>
