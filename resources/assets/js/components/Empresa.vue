<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Empresa
                    <button v-if="estadoInputs" type="button" @click="estadoCampos()" class="btn btn-secondary"
                        style="margin-left: 10px;">
                        <i class="icon-plus"></i>&nbsp;Editar
                    </button>
                </div>
                <br>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label font-weight-bold" for="text-input">&nbsp; Logo de la
                        Empresa:</label>
                    <div class="col-md-4">
                        <div class="row">
                            <figure class="col-md-4">
                                <img :src="logoUrl" width="129" height="129" alt="Logo empresa">
                            </figure>
                            <div v-if="!estadoInputs" class="col-md-8">
                                <input type="file" @change="onLogoChange" accept="image/*" class="form-control-file">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label font-weight-bold" for="text-input">&nbsp; Nombre de la
                        Empresa:</label>
                    <div v-if="!estadoInputs" class="col-md-4 mx-2">
                        <input type="text" v-model="nombre" class="form-control"
                            placeholder="Ingrese el nombre de la empresa" :readonly="this.estadoInputs" maxlength="50">
                    </div>
                    <div v-else class="col-md-4 mx-2">
                        {{ nombre }}
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-md-3 form-control-label font-weight-bold" for="email-input">&nbsp; Dirección de la
                        Empresa</label>
                    <div v-if="!estadoInputs" class="col-md-4 mx-2">
                        <input type="text" v-model="direccion" maxlength="50" class="form-control"
                            placeholder="Ingrese la direccion" :readonly="this.estadoInputs">
                    </div>
                    <div v-else class="col-md-4 mx-2">
                        {{ direccion }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label font-weight-bold" for="email-input">&nbsp; Teléfono de la
                        Empresa</label>
                    <div v-if="!estadoInputs" class="col-md-4 mx-2">
                        <input type="number" v-model="telefono" class="form-control" placeholder="Ingrese el telefono"
                            :readonly="this.estadoInputs">
                    </div>
                    <div v-else class="col-md-4 mx-2">
                        {{ telefono }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label font-weight-bold" for="email-input">&nbsp; NIT de la
                        Empresa</label>
                    <div v-if="!estadoInputs" class="col-md-4 mx-2">
                        <input type="number" v-model="nit" class="form-control" placeholder="Ingrese el telefono"
                            :readonly="this.estadoInputs">
                    </div>
                    <div v-else class="col-md-4 mx-2">
                        {{ nit }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 form-control-label font-weight-bold" for="email-input">&nbsp; Email de la
                        Empresa</label>
                    <div v-if="!estadoInputs" class="col-md-4 mx-2">
                        <input type="email" v-model="email" class="form-control" placeholder="Ingrese el email"
                            :readonly="this.estadoInputs">
                        <!-- <p :style="{color:'red'}" v-if="validEmail === false">Por favor, ingrese un correo electronico de la empresa valido</p> -->
                    </div>
                    <div v-else class="col-md-4 mx-2">
                        {{ email }}
                    </div>

                </div>





                <div v-if="!estadoInputs" class="form-group-row justify-content-center text-center mt-3 mb-3">
                    <button type="button" class="btn btn-danger"
                        @click="estadoCampos(); datosEmpresa()">Cancelar</button>

                    <button type="button" class="btn btn-success" @click="actualizarEmpresa()">Guardar cambios</button>
                </div>

            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            empresa_id: 0,
            nombre: '',
            direccion: '',
            telefono: '',
            email: '',
            nit: '',
            licencia: '',
            estadoInputs: true,
            logo: null,
            logoUrl: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT3bz1rSR914Qj3-mmNDyf-MhhLkdq3GzsVNKUZYXTJaQ&s', // Default image

        }
    },
    methods: {
        validarCorreoElectronico(correo) {
            const regexCorreoElectronico = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regexCorreoElectronico.test(correo);
        },
        validarCampos() {
            if (this.telefono.length > 8) {
                this.toastError("El número de telefono no debe contener mas de 8 digitos")
                return false;
            }
            if (!this.validarCorreoElectronico(this.email)) {
                this.toastError("Ingrese un correo electronico valido")
                return false;
            }
            return true



        },
        estadoCampos() {
            this.estadoInputs = !this.estadoInputs;
        },
        datosEmpresa() {

            let me = this;
            var url = '/empresa';

            axios.get(url).then(function (response) {
                var respuesta = response.data;

                me.empresa_id = respuesta.empresa.id;
                me.nombre = respuesta.empresa.nombre;
                me.direccion = respuesta.empresa.direccion;
                me.telefono = respuesta.empresa.telefono;
                me.email = respuesta.empresa.email;
                me.nit = respuesta.empresa.nit;
                me.licencia = respuesta.empresa.licencia;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        onLogoChange(e) {
            const file = e.target.files[0];
            this.logo = file;
            this.logoUrl = URL.createObjectURL(file);
        },
        actualizarEmpresa() {
            if (!this.validarCampos()) {
                return;
            }

            let formData = new FormData();
            formData.append('nombre', this.nombre);
            formData.append('direccion', this.direccion);
            formData.append('telefono', this.telefono);
            formData.append('email', this.email);
            formData.append('nit', this.nit);
            formData.append('licencia', this.licencia);
            formData.append('id', this.empresa_id);
            if (this.logo) {
                formData.append('logo', this.logo);
            }

            axios.post('/empresa/actualizar', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then((response) => {
                this.toastSuccess("Datos actualizados correctamente");
                this.estadoCampos();
                this.datosEmpresa(); // Refresh data to get the new logo URL
            }).catch((error) => {
                console.error("Ocurrio un error al actualizar: ", error);
                this.toastError("Hubo un error al actualizar los datos de la empresa");
            });
        },
        datosEmpresa() {
            let me = this;
            var url = '/empresa';

            axios.get(url).then(function (response) {
                var respuesta = response.data;

                me.empresa_id = respuesta.empresa.id;
                me.nombre = respuesta.empresa.nombre;
                me.direccion = respuesta.empresa.direccion;
                me.telefono = respuesta.empresa.telefono;
                me.email = respuesta.empresa.email;
                me.nit = respuesta.empresa.nit;
                me.licencia = respuesta.empresa.licencia;
                me.logoUrl = respuesta.empresa.logo || me.logoUrl; // Use the logo from the server if available
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        toastSuccess(mensaje) {
            this.$toasted.show(`
    <div style="height: 60px;font-size:16px;">
        <br>
        `+ mensaje + `.<br>
    </div>`, {
                type: "success",
                position: "bottom-right",
                duration: 4000
            });
        },
        toastError(mensaje) {
            this.$toasted.show(`
    <div style="height: 60px;font-size:16px;">
        <br>
        `+ mensaje + `<br>
    </div>`, {
                type: "error",
                position: "bottom-right",
                duration: 4000
            });
        }
    },
    mounted() {
        this.datosEmpresa()
    }
}
</script>
<style>
.modal-content {
    width: 100% !important;
    position: absolute !important;
}

.mostrar {
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color: #3c29297a !important;
}
</style>