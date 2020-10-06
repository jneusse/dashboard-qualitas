<template>
    <form>
        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">account_circle</i>
                    <input id="name" type="text" class="@error('name') is-invalid @enderror" name="name" required autocomplete="name" autofocus>
                    <label for="name">Nombre
                        <span id="spanName" class="new badge red" data-badge-caption="" role="alert" style="display: none"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">email</i>
                    <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="" required autocomplete="email">
                    <label for="email">Correo electrónico
                        <span id="spanEmail" class="new badge red" data-badge-caption="" role="alert" style="display: none"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">mode_edit</i>
                    <input id="password" pattern=".{0}|.{8,}" title="Contraseña, al menos 8 caracteres" type="password" class="@error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                    <label for="password">Contraseña, al menos 8 caracteres
                        <span id="spanPassword" class="new badge red" role="alert" style="display: none"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">mode_edit</i>
                    <input id="password-confirm" type="password" name="password-confirm" autocomplete="new-password">
                    <label for="password-confirm">Confirmar Contraseña</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s3 offset-s2">
                <div class="input-field">
                    <p>
                        <label for="is_admin">
                            <input id="is_admin" class="checkbox-orange" type="checkbox" name="is_admin"/>
                            <span>Administrador</span>
                        </label>
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s6">
                <div class="input-field left">
                    <a id="cancelar" href="#!" class="modal-close btn grey">Cancelar</a>
                </div>
            </div>
            <div class="col s6">
                <div class="input-field right">
                <button id="btnSubmit" type="submit" class="btn waves-effect">
                        Guardar
                </button>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
export default {
    props:['user', 'errors'],
    computed:{

    }

}
</script>

<style>

</style>
