<template>
  <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a class="text-decoration-none" href="/">Escritorio</a></li>
      </ol>
      <div class="container-fluid">
          <!-- Ejemplo de tabla Listado -->
          <div class="card">
              <div class="card-header">
                  <i class="fa fa-align-justify"></i> Clientes
                  <button type="button" @click="abrirModal('persona', 'registrar')" class="btn btn-secondary">
                      <i class="icon-plus"></i>&nbsp;Nuevo
                  </button>
                  <button type="button" @click="cargarReporteExcel()" class="btn btn-info">
                      <i class="icon-doc"></i>&nbsp;Reporte
                  </button>
                  <button type="button" @click="abrirModalImportar()" class="btn btn-success">
                      <i class="icon-plus"></i>&nbsp;Importar
                  </button>
              </div>
              <div class="card-body">
                  <div class="form-group d-md-flex justify-content-md-between">
                      <div class="col-md-6">
                          <div class="input-group">
                              <select class="form-control col-md-3" v-model="criterio">
                                  <option value="nombre">Nombre</option>
                                  <option value="num_documento">Documento</option>
                                  <option value="email">Email</option>
                                  <option value="telefono">Teléfono</option>
                              </select>
                              <input type="text" v-model="buscar" @keyup="listarPersona(1, buscar, criterio)"
                                  class="form-control" placeholder="Texto a buscar">
                          </div>

                      </div>
                      <div v-if="rolUsuario == 1" class="col-md-3">
                          <label for="selectUsuarioFiltro" class="form-label"><strong>Buscar Cliente por Usuario</strong></label>
                          <v-select id="selectUsuarioFiltro" :on-search="selectUsuarioFiltro" label="nombre"
                              :options="arrayUsuarioFiltro" placeholder="Buscar Usuario..."
                              :onChange="getDatosUsuarioFiltro" v-model="usuarioSeleccionadodos">
                          </v-select>
                      </div>
                  </div>
                  <div class="table-responsive">
                      <table class="table table-bordered table-striped table-sm">
                          <thead>
                              <tr>
                                  <th v-if="rolUsuario == 1">Acciones</th>
                                  <th>Nombres</th>
                                  <th>Tipo Documento</th>
                                  <th>Número de documento</th>
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
                                  <td v-text="getTipoDocumentoText(persona.tipo_documento)"></td>
                                  <td v-text="persona.num_documento"></td>
                              
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
      </div>
      <!--Inicio del modal agregar/actualizar-->
      <div class="modal " tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
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
                              <div>
                                  <label for="nombre" class="font-weight-bold">Nombre del cliente <span
                                          class="text-danger">*</span></label>
                                  <input type="text" v-model="datosFormulario.nombre" class="form-control" id="nombre"
                                      placeholder="Ej. Juan Pérez" :class="{ 'is-invalid': errores.nombre }"
                                      @input="validarCampo('nombre')" style="width:  100%;">
                                  <p class="text-danger" v-if="errores.nombre">{{ errores.nombre }}</p>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-md-6">
                                  <label for="tipo_documento" class="font-weight-bold">Tipo de documento <span
                                          class="text-danger">*</span></label>
                                  <div>
                                      <select class="form-control" v-model="datosFormulario.tipo_documento"
                                          :class="{ 'is-invalid': errores.tipo_documento }"
                                          @change="validarCampo('tipo_documento')">
                                          <option value="" disabled>Selecciona una tipo de documento</option>
                                          <option value="1">CI - CEDULA DE IDENTIDAD</option>
                                          <option value="2">CEX - CEDULA DE IDENTIDAD DE EXTRANJERO</option>
                                          <option value="5">NIT - NÚMERO DE IDENTIFICACIÓN TRIBUTARIA</option>
                                          <option value="3">PAS - PASAPORTE</option>
                                          <option value="4">OD - OTRO DOCUMENTO DE IDENTIDAD</option>   
                                      </select>                                    
                                  </div>
                                  <p class="text-danger" v-if="errores.tipo_documento">{{ errores.tipo_documento }}
                                  </p>
                              </div>
                                  <div class="col-md-3">
                                      <label for="num_documento" class="font-weight-bold">N°
                                          {{ datosFormulario.tipo_documento == "" ? "Documento" : "" }}

                                          {{ datosFormulario.tipo_documento == 1 ? "CI" : "" }}
                                          {{ datosFormulario.tipo_documento == 2 ? "CEX" :
                          "" }}
                                          {{ datosFormulario.tipo_documento == 3 ? "Pasaporte" : "" }}
                                          {{ datosFormulario.tipo_documento == 5 ? "NIT" : "" }}
                                          <span class="text-danger">*</span></label>
                                      <input type="text" v-model="datosFormulario.num_documento" class="form-control"
                                          id="num_documento" placeholder="Ej. 12345678"
                                          :class="{ 'is-invalid': errores.num_documento }"
                                          @input="validarCampo('num_documento')">
                                      <p class="text-danger" v-if="errores.num_documento">{{ errores.num_documento }}</p>
                                  </div>
                                  <div class="col-md-3">
                                      <label class="font-weight-bold" for="complemento">Complemento</label>
                                      <input class="form-check-input ml-2" type="checkbox" id="complemento" v-model="mostrarComplemento">
                                      <input type="text" v-show="mostrarComplemento" v-model="datosFormulario.complemento" class="form-control" id="complemento" placeholder="Ingrese el complemento">
                                  </div>
                          </div>
                          
                          <div class="form-group row">
                          </div>
                          <div v-if="tipoAccion == 2" class="alert alert-info " role="alert">
                              Este cliente fue creado por: <strong>{{ usuarioSeleccionado }}</strong>.
                          </div>
                          <div class="form-group row" v-if="activaredit == true && rolUsuario == 1">
                              <label for="user" class="font-weight-bold">Usuario <span
                                      class="text-danger">*</span></label>
                              <div class="col-md-9">
                                  <v-select :on-search="selectUsuario" label="nombre" :options="arrayUsuarioV"
                                      placeholder="Buscar Usuario..." :onChange="getDatosUsuario"
                                      v-model="usuarioSeleccionado">
                                  </v-select>
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
          <ImportarExcelCliente @cerrar="cerrarModalImportar"></ImportarExcelCliente>
      </div>
  </main>
</template>

<script>
import { esquemaCliente } from '../constants/validations';
import vSelect from 'vue-select';
export default {
  data() {
      return {
          datosFormulario: {
              nombre: '',
              tipo_documento: '',
              num_documento: '',
              complemento: '',
          },
          errores: {},

          rolUsuario: '',
          arrayUsuarioV: [],
          usuarioSeleccionado: '',
          idusuario: '',
          arrayDetalleUsuario: [],
          activaredit: false,
          arrayUsuarioFiltro: [],
          usuarioSeleccionadodos: '',
          usuariodos_id: '',
          role_id: '',
          mostrarComplemento: false,
          modalImportar: 0,

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
          criterio: 'nombre',
          buscar: ''
      }
  },
  components: {
      vSelect
  },
  computed: {
      isActived: function () {
          return this.pagination.current_page;
      },
      pagesNumber: function () {
          if (!this.pagination.to) {
              return [];
          }

          let from = this.pagination.current_page - this.offset;
          if (from < 1) {
              from = 1;
          }

          let to = from + (this.offset * 2);
          if (to >= this.pagination.last_page) {
              to = this.pagination.last_page;
          }
          let pagesArray = [];
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
              await esquemaCliente.validateAt(campo, this.datosFormulario);
              this.errores[campo] = null;
          } catch (error) {
              this.errores[campo] = error.message;
          }
      },
      async enviarFormulario() {

          await esquemaCliente.validate(this.datosFormulario, { abortEarly: false })
              .then(() => {
                  if (this.tipoAccion == 1) {
                      this.registrarPersona(this.datosFormulario);
                  } else {
                      this.datosFormulario.usuariodos_id = this.idusuario
                      this.actualizarPersona(this.datosFormulario);
                  }
                  this.mostrarComplemento = false;
              })
              .catch((error) => {
                  const erroresValidacion = {};
                  error.inner.forEach((e) => {
                      erroresValidacion[e.path] = e.message;
                  });

                  this.errores = erroresValidacion;
              });
      },

      listarPersona(page, buscar, criterio) {
          let me = this;
          let url = '/cliente?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&usuarioid=' + me.usuariodos_id;
          axios.get(url).then(function (response) {
              let respuesta = response.data;
              me.arrayPersona = respuesta.usuarios.data;
              me.pagination = respuesta.pagination;
          })
              .catch(function (error) {
                  console.log(error);
              });
      },
      //---selecionar busqueda---
      selectUsuarioFiltro(search, loading) {
          let me = this;
          loading(true)
          let url = '/cliente/usuario/filtro?filtro=' + search;
          axios.get(url).then(function (response) {
              let respuesta = response.data;
              q: search
              me.arrayUsuarioFiltro = respuesta.usuariodos;
              loading(false)
          })
              .catch(function (error) {
                  console.log(error);
              });
      },
      // limpiarUsuarioSeleccionado(newValue) {
      //     console.log("Xd")
      //     if (newValue === null) {
      //         console.log('ENTRO##');
      //     }
      // },
      getDatosUsuarioFiltro(val1) {
          let me = this;
          me.loading = true;

          if (val1 && val1.iduse) {
              me.usuariodos_id = val1.iduse;
              me.usuarioSeleccionadodos = val1.nombre;
              me.listarPersona(1, this.buscar, this.criterio);
          }
      },
      cambiarPagina(page, buscar, criterio) {
          let me = this;
          me.pagination.current_page = page;
          me.listarPersona(page, buscar, criterio);
      },
      registrarPersona(datos) {
          let me = this;

          axios.post('/cliente/registrar', datos).then(function (response) {
              me.cerrarModal();
              me.listarPersona(1, '', 'nombre');
          }).catch(function (error) {
              console.log(error);
          });
      },
      actualizarPersona(datos) {
          let me = this;

          axios.put('/cliente/actualizar', datos).then(function (response) {
              me.cerrarModal();
              me.listarPersona(1, '', 'nombre');
          }).catch(function (error) {
              console.log(error);
          });
      },
      cerrarModal() {
          this.modal = 0;
          this.tituloModal = '';
          this.errorPersona = 0;
          this.activaredit = false;
          this.idusuario = '';

      },
      abrirModal(modelo, accion, data = []) {
          switch (modelo) {
              case "persona":
                  {
                      switch (accion) {
                          case 'registrar':
                              {
                                  this.modal = 1;
                                  this.tituloModal = 'Registrar Cliente';
                                  this.tipoAccion = 1;
                                  this.datosFormulario = {
                                      nombre: '',
                                      tipo_documento: '',
                                      num_documento: '',
                                      complemento: '',
                                      direccion: '',
                                      telefono: '',
                                      email: ''
                                  };
                                  break;
                              }
                          case 'actualizar':
                              {
                                  this.modal = 1;
                                  this.tituloModal = 'Actualizar Cliente';
                                  this.datosFormulario = {
                                      nombre: data['nombre'],
                                      tipo_documento: data['tipo_documento'],
                                      num_documento: data['num_documento'],
                                      complemento: data['complemento_id'],
                                      direccion: data['direccion'],
                                      telefono: data['telefono'],
                                      email: data['email'],
                                      usuariodos_id: '',
                                      id: data['id']
                                  };
                                  this.tipoAccion = 2;
                                  this.activaredit = true;
                                  this.verUsuario(data);
                                  break;
                              }
                      }
                  }
          }
      },
      cargarReporteExcel() {
          window.open('/cliente/listarReporteClienteExcel', '_blank');
      },
      getTipoDocumentoText(value) {
          switch (value) {
              case '1':
                  return 'CI';
              case '2':
                  return 'CEX';
              case '4':
                  return 'NIT';
              case '3':
                  return 'PAS';
              default:
                  return '';
          }
      },
      recuperarIdRol() {
          this.rolUsuario = window.userData.rol;
      },
      selectUsuario(search, loading) {
          let me = this;
          loading(true)
          let url = '/cliente/selectUusarioVend?filtro=' + search;
          axios.get(url).then(function (response) {
              //console.log(response.clientes);
              let respuesta = response.data;
              q: search
              me.arrayUsuarioV = respuesta.clientes;
              loading(false)
          })
              .catch(function (error) {
                  console.log(error);
              });
      },
      getDatosUsuario(val1) {
          let me = this;
          me.loading = true;

          if (val1 && val1.ID_use) {
              me.idusuario = val1.ID_use;
              me.usuarioSeleccionado = val1.nombre;
          }
      },
      verUsuario(data) {
          this.idusuario = data.usuario;
          let me = this;
          let url = '/cliente/usuario?idusuario=' + this.idusuario;
          axios.get(url)
              .then(function (response) {
                  var respuesta = response.data;
                  me.arrayDetalleUsuario = respuesta.usuario;
                  me.usuarioSeleccionado = me.arrayDetalleUsuario[0].nombre;

              })
              .catch(function (error) {
                  console.log(error);
              });
      },

      listarDatosuser() {
          axios.get('/user-info')
              .then(response => {
                  const userData = response.data.user;
                  this.usuariodos_id = userData.iduse;
                  this.role_id = userData.idrol;
                  if (this.role_id == 1) {
                      this.usuariodos_id = '';
                      this.listarPersona(1, this.buscar, this.criterio);
                  } else {
                      this.listarPersona(1, this.buscar, this.criterio);
                  }
              })
              .catch(error => {
                  console.error('Error al obtener la información del usuario:', error);
              });
      },

      abrirModalImportar() {
          this.modalImportar = 1;
      },
      cerrarModalImportar() {
          this.modalImportar = 0;
          this.listarPersona(1, '', 'nombre');
      },
  },

  mounted() {
      this.listarDatosuser();
      this.recuperarIdRol();
  },
}
</script>