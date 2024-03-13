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
                        <button type="button" @click="abrirModal('persona','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                        <button type="button" @click="cargarReporteExcel()" class="btn btn-info">
                            <i class="icon-doc"></i>&nbsp;Reporte
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="num_documento">Documento</option>
                                      <option value="email">Email</option>
                                      <option value="telefono">Teléfono</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup="listarPersona(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarPersona(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                            <div v-if="rolUsuario == 1" class="col-md-5">
                                <v-select 
                                    :on-search="selectUsuarioFiltro"  
                                    label="nombre" 
                                    :options="arrayUsuarioFiltro"
                                    placeholder="Buscar Usuario..."
                                    :onChange="getDatosUsuarioFiltro"
                                    v-model="usuarioSeleccionadodos"
                                    @change="limpiarUsuarioSeleccionado"
                                    >
                                </v-select>  
                            </div>
                        </div>
                        <!-- <div class="form-group row">
                            <div class="col-md-5">
                                <v-select 
                                            :on-search="selectUsuario"  
                                            label="nombre" 
                                            :options="arrayUsuarioV"
                                            placeholder="Buscar Usuario..."
                                            :onChange="getDatosUsuario"
                                            v-model="usuarioSeleccionado"
                                            >
                                        </v-select>  
                            </div>
                        </div> -->
                        <div class="table-responsive">

                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th v-if="rolUsuario == 1">Opciones</th>
                                    <th>Nombre</th>
                                    <th>Tipo Documento</th>
                                    <th>Número</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="persona in arrayPersona" :key="persona.id">
                                    <!-- <td v-if="rolUsuario == 1">
                                        <button type="button" @click="abrirModal('persona','actualizar',persona)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>
                                    </td> -->
                                    <td>
                                        <button type="button" @click="abrirModal('persona','actualizar',persona)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button>
                                    </td>
                                    <td v-text="persona.nombre"></td>
                                    <td v-text="getTipoDocumentoText(persona.tipo_documento)"></td>
                                    <td v-text="persona.num_documento"></td>
                                    <td v-text="persona.direccion"></td>
                                    <td v-text="persona.telefono"></td>
                                    <td v-text="persona.email"></td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>

                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal " tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre (*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la persona">                                        
                                    </div>
                                </div>
                                <div class="form-group row" v-if="activaredit == true && rolUsuario == 1">
                                    <label class="col-md-3 form-control-label" for="text-input">Usuario*</label>
                                    <div class="col-md-9">
                                        <v-select 
                                            :on-search="selectUsuario"  
                                            label="nombre" 
                                            :options="arrayUsuarioV"
                                            placeholder="Buscar Usuario..."
                                            :onChange="getDatosUsuario"
                                            v-model="usuarioSeleccionado"
                                            >
                                        </v-select>    
                                        <!-- :onChange="getDatosCliente"                   -->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo Documento</label>
                                    <div class="col-md-9">
                                        <select v-model="tipo_documento" class="form-control">
                                            <option value="" disabled>Selecciona una tipo de documento</option>
                                            <option value="1">CI - CEDULA DE IDENTIDAD</option>
                                            <option value="2">CEX - CEDULA DE IDENTIDAD DE EXTRANJERO</option>
                                            <option value="5">NIT - NÚMERO DE IDENTIFICACIÓN TRIBUTARIA</option>
                                            <option value="3">PAS - PASAPORTE</option>
                                            <option value="4">OD - OTRO DOCUMENTO DE IDENTIDAD</option>   
                                        </select>                                    
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Número</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="num_documento" class="form-control" placeholder="Número de documento">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                                <div v-show="errorPersona" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    import vSelect from 'vue-select';
    export default {
        data (){
            return {
                rolUsuario: '',
                arrayUsuarioV :[],
                usuarioSeleccionado:'',
                idusuario: '',
                arrayDetalleUsuario: [],
                activaredit: false,
                arrayUsuarioFiltro: [],
                usuarioSeleccionadodos: '',
                usuariodos_id: '',
                role_id : '',

                persona_id: 0,
                nombre : '',
                tipo_documento : 'DNI',
                num_documento : '',
                direccion : '',
                telefono : '',
                email : '',
                arrayPersona : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPersona : 0,
                errorMostrarMsjPersona : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                //criterio : '',
                buscar : ''
            }
        },
        components: {
            vSelect
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {
            
            listarPersona (page,buscar,criterio){
                let me=this;
                console.log('llega??',me.usuariodos_id);
                var url= '/cliente?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio + '&usuarioid=' + me.usuariodos_id;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    //me.arrayPersona = respuesta.personas.data;
                    me.arrayPersona = respuesta.usuarios.data;
                    console.log('LIST',me.arrayPersona);
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            //---selecionar busqueda---
            selectUsuarioFiltro(search, loading) {
                let me = this;
                loading(true)
                //console.log('llega??',me.iduse);
                var url = '/cliente/usuario/filtro?filtro=' + search;
                axios.get(url).then(function (response) {
                    //console.log(response.clientes);
                    let respuesta = response.data;
                    console.log('BUSCADO2', respuesta);
                    q: search
                    me.arrayUsuarioFiltro = respuesta.usuariodos;
                    console.log('BUSCADO22', me.arrayUsuarioFiltro);
                    loading(false)
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            limpiarUsuarioSeleccionado(newValue) {
                if (newValue === null) {
                    console.log('ENTRO##');
                    //this.usuarioSeleccionadodos = null;
                }
                // this.usuarioSeleccionadodos = null;
                // this.usuariodos_id = null;
               // this.listarPersona(1,this.buscar,this.criterio);
            },
             //--SELECCIONAR OSEA AGARRA EL ID DEL USUARIO 
             getDatosUsuarioFiltro(val1) {
                let me = this;
                me.loading = true;

                if (val1 && val1.iduse) {
                    me.usuariodos_id = val1.iduse;
                    console.log('ID_USER2', me.usuariodos_id);
                    me.usuarioSeleccionadodos = val1.nombre;
                    console.log('NOBRE2', me.usuarioSeleccionadodos);
                    me.listarPersona(1,this.buscar,this.criterio);
                }
                //this.listarPersona(1,this.buscar,this.criterio);
            },
            //-------------------
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarPersona(page,buscar,criterio);
            },
            registrarPersona(){
                if (this.validarPersona()){
                    return;
                }
                
                let me = this;

                axios.post('/cliente/registrar',{
                    'nombre': this.nombre,
                    'tipo_documento': this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarPersona(){
               if (this.validarPersona()){
                    return;
                }
                
                let me = this;

                axios.put('/cliente/actualizar',{
                    'nombre': this.nombre,
                    'usuariodos_id': this.idusuario,
                    'tipo_documento': this.tipo_documento,
                    'num_documento' : this.num_documento,
                    'direccion' : this.direccion,
                    'telefono' : this.telefono,
                    'email' : this.email,
                    'id': this.persona_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarPersona(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },            
            validarPersona(){
                this.errorPersona=0;
                this.errorMostrarMsjPersona =[];

                if (!this.nombre) this.errorMostrarMsjPersona.push("El nombre de la persona no puede estar vacío.");

                if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

                return this.errorPersona;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.tipo_documento='DNI';
                this.num_documento='';
                this.direccion='';
                this.telefono='';
                this.email='';
                this.errorPersona=0;
                this.activaredit = false;
                this.idusuario = '';

            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "persona":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Cliente';
                                this.nombre= '';
                                this.tipo_documento='DNI';
                                this.num_documento='';
                                this.direccion='';
                                this.telefono='';
                                this.email='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Cliente';
                                this.tipoAccion=2;
                                this.persona_id=data['id'];
                                this.nombre = data['nombre'];
                                this.tipo_documento = data['tipo_documento'];
                                this.num_documento = data['num_documento'];
                                this.direccion = data['direccion'];
                                this.telefono = data['telefono'];
                                this.email = data['email'];
                                this.activaredit = true;
                                // this.usuariodos_id = data['usuario'];
                                // console.log('Usuario_dos',this.usuariodos_id);
                                //this.idusuario = data['usuario'];
                                this.verUsuario(data);
                                break;
                            }
                        }
                    }
                }
            },
            cargarReporteExcel()
            {
                window.open('/cliente/listarReporteClienteExcel', '_blank');
            },
            getTipoDocumentoText(value) {
            switch(value) {
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
            //----------
            recuperarIdRol() {
                this.rolUsuario = window.userData.rol;
                console.log('ID_ROL: ' + this.rolUsuario);
            },
            selectUsuario(search, loading) {
                let me = this;
                loading(true)
                var url = '/cliente/selectUusarioVend?filtro=' + search;
                axios.get(url).then(function (response) {
                    //console.log(response.clientes);
                    let respuesta = response.data;
                    q: search
                    me.arrayUsuarioV = respuesta.clientes;
                    console.log('BUSCADO', me.arrayUsuarioV);
                    loading(false)
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            
            //--SELECCIONAR OSEA AGARRA EL ID DEL USUARIO 
            getDatosUsuario(val1) {
                let me = this;
                me.loading = true;

                if (val1 && val1.ID_use) {
                    me.idusuario = val1.ID_use;
                    console.log('ID_USER', me.idusuario);
                    me.usuarioSeleccionado = val1.nombre;
                    console.log('NOBRE', me.usuarioSeleccionado);
                }
            },
            verUsuario(data) {
                //let idusuario = data.usuario; 
                this.idusuario = data.usuario; 
                console.log('RECUPERO!!', this.idusuario);
                let me = this;

                //me.arrayPedidoSeleccionado=data;

                var url = '/cliente/usuario?idusuario=' + this.idusuario;
                axios.get(url)
                    .then(function (response) {
                    var respuesta = response.data;
                    //me.arrayPedidoProvDet = respuesta.pedidoprov; // Corrección aquí
                    me.arrayDetalleUsuario = respuesta.usuario;
                    console.log('ARRAY', me.arrayDetalleUsuario);
                    me.usuarioSeleccionado = me.arrayDetalleUsuario[0].nombre;
                    console.log('Nombre del usuario:', me.arrayDetalleUsuario[0].nombre);
                    
                    })
                    .catch(function (error) {
                    console.log(error);
                });              
            },
            // listarPrueba(page,buscar,criterio){
            //     let me=this;
            //     var url= '/cliente/selectUusarioVend?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
            //     axios.get(url).then(function (response) {
            //         var respuesta= response.data;
            //         console.log('PRUEVA:',respuesta);
            //     })
            //     .catch(function (error) {
            //         console.log(error);
            //     });
            // },
            listarDatosuser(){
                axios.get('/user-info')
                .then(response => {
                    // Aquí response.data.user contiene la información del usuario, incluido el ID
                    //const userId = response.data.user.id;
                    const userData = response.data.user;
                    console.log('DAtOS RECUPERADO:', userData);
                    // Puedes almacenar userId en el estado de tu componente Vue.js o utilizarlo según sea necesario
                    this.usuariodos_id = userData.iduse;
                    console.log('DAtOS RECUPERADO IS_USE:', this.usuariodos_id);
                    this.role_id = userData.idrol;
                    console.log('DAtOS RECUPERADO role_id:', this.role_id);
                    // this.listarPersona(1,this.buscar,this.criterio);
                    
                    if(this.role_id == 1){
                        this.usuariodos_id = '';
                        this.listarPersona(1,this.buscar,this.criterio);
                    } else{
                        this.listarPersona(1,this.buscar,this.criterio);
                    }
                })
                .catch(error => {
                    console.error('Error al obtener la información del usuario:', error);
                });
            },
        },
        
        mounted() {
            this.listarDatosuser();
            //this.listIndex();
            //this.listarPersona(1,this.buscar,this.criterio);
            this.recuperarIdRol();
            //this.listarPrueba();
        },
        // created(){
        // }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
