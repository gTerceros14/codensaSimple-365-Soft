<template>
    <main class="main">
      <!-- Breadcrumb -->
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
      </ol>
      <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
          <div class="card-header">
            <i class="fa fa-align-justify"></i> Ofertas
            <icon-button icon="icon-plus" size="small" color="secondary"  @click="abrirModal('oferta', 'registrar')" label="Nuevo"/>
          </div>
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6">
                <div class="input-group">
                  <select class="form-control col-md-3" v-model="criterio">
                    <option value="nombre">Nombres</option>
                  </select>
                  <input type="text" v-model="buscar" @keyup.enter="listarOfertas(1, buscar, criterio)"
                    class="form-control" placeholder="Texto a buscar">
                  <button type="submit" @click="listarOfertas(1, buscar, criterio)" class="btn btn-primary"><i
                      class="fa fa-search"></i> Buscar</button>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>Acciones</th>
                    <th>Nombre oferta</th>
                    <th>Descripción</th>
                    <th>Descuento</th>
                    <th>Cantidad en stock</th>
                    <th>Fecha limite</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="oferta in arrayOfertas" :key="oferta.id">
                    <td>
                      <icon-button icon="icon-pencil" size="small" color="warning" @click="abrirModal('oferta', 'actualizar', oferta)" />
                      <icon-button v-if="oferta.condicion" icon="icon-trash" size="small" color="danger" @click="desactivarOferta(oferta.id)" />
                      <icon-button v-else icon="icon-check" size="small" color="info"  @click="activarOferta(oferta.id)"/>
                    </td>
                    <td v-text="oferta.nombre"></td>
                    <td>
                      <div v-if="oferta.condicion">
                        <span class="badge badge-success">Activo</span>
                      </div>
                      <div v-else>
                        <span class="badge badge-danger">Desactivado</span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <nav>
              <ul class="pagination mt-2">
                <li class="page-item" v-if="pagination.current_page > 1">
                  <a class="page-link" href="#"
                    @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Anterior</a>
                </li>
                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                </li>
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                  <a class="page-link" href="#"
                    @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Siguiente</a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
      </div>
      <!-- Inicio del modal agregar/actualizar -->
      <div class="modal fade" tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
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
  
            <div class="modal-body" >
                  <div class="form-group row">
                    <div class="col-md-8">
                      <label for="" class="font-weight-bold">Nombre de la oferta <span
                          class="text-danger">*</span></label>
                      <input type="text" v-model="datosFormulario.nombre" class="form-control"
                        placeholder="Ej. Promoción diciembre"  :class="{ 'is-invalid': errores.nombre }"  @input="validarCampo('nombre')"/>
                      <p class="text-danger" v-if="errores.nombre">{{ errores.nombre }}</p>
                    </div>
                  </div>  
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="cerrarModal()">Cerrar</button>
              <button type="submit" v-if="tipoAccion == 1" class="btn btn-success"
                >Guardar</button>
              <button type="submit" v-if="tipoAccion == 2" class="btn btn-success"
              >Actualizar</button>
            </div>
          </form>
  
          </div>
        </div>
      </div>
      <!-- Fin del modal -->
    </main>
  </template>
    
  <script>
  import { esquemaOfertas } from '../constants/validations';
  export default {
    data() {
      return {
        datosFormulario: {
          nombre: '',
        //   agrega mas datos
        },
        errores: {},
  
        arrayOfertas: [],
        modal: 0,
        tituloModal: '',
        tipoAccion: 0,
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
        arrayEmpresa: [],
        buscar: ''
      }
    },
    computed: {
      isActived: function () {
        return this.pagination.current_page;
      },
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
          await esquemaOfertas.validateAt(campo, this.datosFormulario);
          this.errores[campo] = null;
        } catch (error) {
          this.errores[campo] = error.message;
        }
      },
      async enviarFormulario() {
       
        await esquemaOfertas.validate(this.datosFormulario, { abortEarly: false })
          .then(() => {
            if (this.tipoAccion == 2) {
              this.actualizarOFerta(this.datosFormulario);
            } else {
              this.registrarOferta(this.datosFormulario);
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
  
      listarOfertas(page, buscar, criterio) {
        let me = this;
        let url ='INGRESE URL LISTAR OFERTA';
        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.arrayOfertas = respuesta.ofertas.data;
          me.pagination = respuesta.pagination;
        })
          .catch(function (error) {
            console.log(error);
          });
      },
      cambiarPagina(page, buscar, criterio) {
        let me = this;
        me.pagination.current_page = page;
        me.listarOfertas(page, buscar, criterio);
      },

  
      registrarOferta(datos) {
        axios.post('URL OFERTA REGISTRAR', datos)
          .then((response) => {
            swal(
              'OFERTA REGISTRADO',
              'Correctamente',
              'success'
            );
            this.cerrarModal();
            this.listarOfertas(1, '', 'nombre');
          })
          .catch((error) => {
            console.log(error);
            swal(
              'ERROR AL REGISTRAR EL NUEVO OFERTA',
              'Intente de nuevo',
              'error'
            );
          });
      },
  
  
      actualizarOFerta(datos) {
  
        axios.put('URL PARA ACTUALIZAR EL OFERTA', datos)
          .then((response) => {
            swal(
              'OFERTA ACTUALIZADO',
              'Correctamente',
              'success'
            );
            this.cerrarModal();
            this.listarOfertas(1, '', 'nombre');
          })
          .catch((error) => {
            console.log(error);
            swal(
              'ERROR AL ACTUALIZAR EL OFERTA',
              'Intente de nuevo',
              'error'
            );
          });
      },
      desactivarOferta(id) {
        swal({
          title: 'Esta seguro de desactivar este oferta?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar',
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: false,
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let me = this;
  
            axios.put('url de desactivar oferta', {
              'id': id
            }).then(function (response) {
              me.listarOfertas(1, '', 'nombre');
              swal(
                'Desactivado!',
                'El registro ha sido desactivado con éxito.',
                'success'
              )
            }).catch(function (error) {
              console.log(error);
            });
  
  
          } else if (
            result.dismiss === swal.DismissReason.cancel
          ) {
  
          }
        })
      },
      activarOferta(id) {
        swal({
          title: 'Esta seguro de activar este oferta?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Aceptar!',
          cancelButtonText: 'Cancelar',
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: false,
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            let me = this;
  
            axios.put('/oferta/activar', {
              'id': id
            }).then(function (response) {
              me.listarOfertas(1, '', 'nombre');
              swal(
                'Activado!',
                'El registro ha sido activado con éxito.',
                'success'
              )
            }).catch(function (error) {
              console.log(error);
            });
  
  
          } else if (
            result.dismiss === swal.DismissReason.cancel
          ) {
  
          }
        })
      },
      cerrarModal() {
        this.modal = 0;
        this.tituloModal = '';
        this.errores = {};
      },
      abrirModal(modelo, accion, data = []) {
        switch (modelo) {
          case "oferta":
            {
              switch (accion) {
                case 'registrar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Registrar nuevo oferta';
                    this.datosFormulario = {
                      nombre: '',
                      direccion: '',
                      correo: '',
                      telefono: '',
                      departamento: '',
                    },
                    this.tipoAccion = 1;
  
                    break;
                  }
                case 'actualizar':
                  {
                    this.modal = 1;
                    this.tituloModal = 'Actualizar oferta';
                    this.tipoAccion = 2;
                    this.datosFormulario = {
                      id: data['id'],
                      idempresa: data['idempresa'],
                      nombre: data['nombre'],
                      direccion: data['direccion'],
                      correo: data['correo'],
                      telefono: data['telefono'],
                      departamento: data['departamento'],
                    };
                    break;
                  }
              }
            }
        }
      }
    },
    mounted() {
      this.listarOfertas(1, this.buscar, this.criterio);
    }
  }
  </script>
  
  