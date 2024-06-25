<template>
    <main class="main">

        <!-- Ejemplo de tabla Listado -->

        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Proveedores
                <button type="button" @click="abrirModal('persona', 'registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
                <button type="button" @click="abrirModalImportar()" class="btn btn-success">
                    <i class="icon-plus"></i>&nbsp;Importar
                </button>

            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" v-model="buscar" @input="buscarProveedores" class="form-control"
                                placeholder="Texto a buscar">
                            <button type="button" @click="listarPersona(1, buscar, criterio)" class="btn btn-primary"><i
                                    class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Opciones</th>
                                <th>Nombre_proveedor</th>
                                <th class="d-none d-md-table-cell">Tipo Documento</th>
                                <th>NIT/CI</th>
                                <!-- <th>Número</th> -->
                                <th class="d-none d-md-table-cell">Dirección</th>
                                <th>Teléfono</th>
                                <th class="d-none d-md-table-cell">Email</th>
                                <th class="d-none d-md-table-cell">Contacto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="persona in arrayPersona" :key="persona.id">
                                <td>
                                    <button type="button" @click="abrirModal('persona', 'actualizar', persona)"
                                        class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button>
                                </td>
                                <td v-text="persona.nombre"></td>
                                <td class="d-none d-md-table-cell"
                                    v-text="getTipoDocumentoText(persona.tipo_documento)">
                                </td>
                                <td v-text="persona.num_documento"></td>
                                <td class="d-none d-md-table-cell" v-text="persona.direccion"></td>
                                <td v-text="persona.telefono"></td>
                                <td class="d-none d-md-table-cell" v-text="persona.email"></td>
                                <td class="d-none d-md-table-cell" v-text="persona.contacto"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#"
                                @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page"
                            :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)"
                                v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#"
                                @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->

        <!--Inicio del modal agregar/actualizar-->
        <div class="modal" tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form @submit.prevent="enviarFormulario">

                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Nombre del proveedor <span
                                            class="text-danger">*</span></label>
                                    <input type="text" v-model="datosFormulario.nombre" class="form-control"
                                        placeholder="Ej. Proveedores S.A." :class="{ 'is-invalid': errores.nombre }"
                                        @input="validarCampo('nombre')">
                                    <p class="text-danger" v-if="errores.nombre">{{ errores.nombre }}</p>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Dirección <span
                                            class="text-danger">*</span></label>
                                    <input type="text" v-model="datosFormulario.direccion" class="form-control"
                                        placeholder="Ej. Calle Falsa 123" :class="{ 'is-invalid': errores.direccion }"
                                        @input="validarCampo('direccion')">
                                    <p class="text-danger" v-if="errores.direccion">{{ errores.direccion }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="tipo_documento" class="font-weight-bold">Tipo de documento <span
                                            class="text-danger">*</span></label>
                                    <select v-model="datosFormulario.tipo_documento" class="form-control"
                                        :class="{ 'is-invalid': errores.tipo_documento }"
                                        @change="validarCampo('tipo_documento')">
                                        <option value="" disabled>Selecciona un tipo de documento</option>
                                        <option v-for="documento in tiposDocumentos" :value="documento.valor"
                                            :key="documento.valor">{{ documento.etiqueta }}</option>
                                    </select>
                                    <p class="text-danger" v-if="errores.tipo_documento">{{ errores.tipo_documento }}
                                    </p>
                                </div>

                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Nro de documento<span
                                            class="text-danger">*</span></label>
                                    <input type="text" v-model="datosFormulario.num_documento" class="form-control"
                                        placeholder="Ej. 1234567890" :class="{ 'is-invalid': errores.num_documento }"
                                        @input="validarCampo('num_documento')">
                                    <p class="text-danger" v-if="errores.num_documento">{{ errores.num_documento }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Correo electrónico <span
                                            class="text-danger">*</span></label>
                                    <input type="email" v-model="datosFormulario.email" class="form-control"
                                        placeholder="Ej. proveedor@ejemplo.com" :class="{ 'is-invalid': errores.email }"
                                        @input="validarCampo('email')">
                                    <p class="text-danger" v-if="errores.email">{{ errores.email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Teléfono <span
                                            class="text-danger">*</span></label>
                                    <input type="number" v-model="datosFormulario.telefono" class="form-control"
                                        placeholder="Ej. 1234567890" :class="{ 'is-invalid': errores.telefono }"
                                        @input="validarCampo('telefono')">
                                    <p class="text-danger" v-if="errores.telefono">{{ errores.telefono }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Contacto <span
                                            class="text-danger">*</span></label>
                                    <input type="text" v-model="datosFormulario.contacto" class="form-control"
                                        placeholder="Ej. Juan Pérez" :class="{ 'is-invalid': errores.contacto }"
                                        @input="validarCampo('contacto')">
                                    <p class="text-danger" v-if="errores.contacto">{{ errores.contacto }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Teléfono de contacto <span
                                            class="text-danger">*</span></label>
                                    <input type="number" v-model="datosFormulario.telefono_contacto"
                                        class="form-control" placeholder="Ej. 0987654321"
                                        :class="{ 'is-invalid': errores.telefono_contacto }"
                                        @input="validarCampo('telefono_contacto')">
                                    <p class="text-danger" v-if="errores.telefono_contacto">{{ errores.telefono_contacto
                                        }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
                            <button type="submit" v-if="tipoAccion == 1" class="btn btn-success">Guardar</button>
                            <button type="submit" v-if="tipoAccion == 2" class="btn btn-success">Actualizar</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
        <div v-if="modalImportar">
            <ImportarExcelProvedores @cerrar="cerrarModalImportar" />
        </div>
    </main>
</template>

<script>
import { esquemaProveedor } from '../constants/validations';

export default {
    data() {
        return {
            datosFormulario: {
                nombre: '',
                tipo_documento: '',
                num_documento: '',
                direccion: '',
                telefono: '',
                email: '',
                contacto: '',
                telefono_contacto: ''
            },

            errores: {},
            tiposDocumentos: [
                { valor: "1", etiqueta: "CI - CEDULA DE IDENTIDAD" },
                { valor: "2", etiqueta: "CEX - CEDULA DE IDENTIDAD DE EXTRANJERO" },
                { valor: "5", etiqueta: "NIT - NÚMERO DE IDENTIFICACIÓN TRIBUTARIA" },
                { valor: "3", etiqueta: "PAS - PASAPORTE" },
                { valor: "4", etiqueta: "OD - OTRO DOCUMENTO DE IDENTIDAD" }
            ],
            persona_id: 0,
            arrayPersona: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorPersona: 0,
            errorMostrarMsjPersona: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: 'todos',
            buscar: '',
            modalImportar: 0,
        }
    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        //Calcula los elementos de la paginación
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;

        }
    },
    methods: {
        async validarCampo(campo) {
            try {
                await esquemaProveedor.validateAt(campo, this.datosFormulario);
                this.errores[campo] = null;
            } catch (error) {
                this.errores[campo] = error.message;
            }
        },
        async enviarFormulario() {

            await esquemaProveedor.validate(this.datosFormulario, { abortEarly: false })
                .then(() => {
                    if (this.tipoAccion == 2) {
                        this.actualizarPersona(this.datosFormulario);
                    } else {
                        this.registrarPersona(this.datosFormulario);
                    }
                })
                .catch((error) => {
                    const erroresValidacion = {};
                    error.inner.forEach((e) => {
                        erroresValidacion[e.path] = e.message;
                    });

                    this.errores = erroresValidacion;
                });
        },
        getTipoDocumentoText(value) {
            const documento = this.tiposDocumentos.find(doc => doc.valor === value);
            return documento ? documento.etiqueta : '';
        },

        abrirModalImportar() {
            this.modalImportar = 1;
        },
        cerrarModalImportar() {
            this.modalImportar = 0;
            this.listarPersona(1, '', 'nombre');
        },
        listarPersona(page, buscar, criterio) {
            let me = this;
            var url = `/proveedor?page=${page}&buscar=${buscar}&criterio=${criterio}`;
            axios.get(url)
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayPersona = respuesta.personas.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        buscarProveedores() {
            // Llama a listarPersona cuando se modifica el valor de buscar
            this.listarPersona(1, this.buscar, this.criterio);
        },




        //listarPersona(page, buscar, criterio) {
        //    let me = this;
        //    var url = '/proveedor?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
        //    axios.get(url).then(function (response) {
        //        var respuesta = response.data;
        //        me.arrayPersona = respuesta.personas.data;
        //        me.pagination = respuesta.pagination;
        //    })
        //        .catch(function (error) {
        //            console.log(error);
        //        });
        //},
        cambiarPagina(page, buscar, criterio) {
            let me = this;
            me.pagination.current_page = page;
            me.listarPersona(page, buscar, criterio);
        },
        registrarPersona(data) {

            console.log("Registrando");
            console.log(data);
            let me = this;

            axios.post('/proveedor/registrar', data).then(function (response) {
                me.cerrarModal();
                me.listarPersona(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
        },
        actualizarPersona(data) {
            let me = this;

            axios.put('/proveedor/actualizar', data).then(function (response) {
                me.cerrarModal();
                me.listarPersona(1, '', 'nombre');
            }).catch(function (error) {
                console.log(error);
            });
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';

        },
        abrirModal(modelo, accion, data = []) {
            switch (modelo) {
                case "persona":
                    {
                        switch (accion) {
                            case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Proveedor';
                                    this.tipoAccion = 1;
                                    this.datosFormulario = {
                                        nombre: '',
                                        tipo_documento: '',
                                        num_documento: '',
                                        direccion: '',
                                        telefono: '',
                                        email: '',
                                        contacto: '',
                                        telefono_contacto: ''
                                    };
                                    break;
                                }
                            case 'actualizar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Actualizar Proveedor';
                                    this.tipoAccion = 2;
                                    this.datosFormulario = data;
                                    this.persona_id = data['id'];
                                    break;
                                }
                        }
                    }
            }
        }
    },
    mounted() {
        this.listarPersona(1, this.buscar, this.criterio);
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

.div-error {
    display: flex;
    justify-content: center;
}

.text-error {
    color: red !important;
    font-weight: bold;
}
</style>
