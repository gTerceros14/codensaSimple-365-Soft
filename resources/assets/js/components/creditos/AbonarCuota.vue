<template>
   <div class="modal " tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-if="cuota">Abonar cuota N°{{  cuota.numero_cuota}}</h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                        <div class="modal-body" v-if="cuota">

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Fecha de pago</label>
                                    <input type="text" disabled :value="formatFecha(cuota.fecha_pago)" class="form-control"
                                        placeholder="Fecha de pago"
                                        >
                                </div>
                                <div class="col-md-6" v-if="arrayCuotas">
                                    <label for="" class="font-weight-bold">Saldo restante</label>
                                    <div class="input-group mb-3">
                                        <input v-if="cuota.numero_cuota>1" type="text" disabled :value="(parseFloat(arrayCuotas[cuota.numero_cuota-2].saldo_restante*moneda[0])-parseFloat(datosFormulario.montoPagar)) " class="form-control">
                                    <input v-else type="text" disabled :value="(cuota.saldo_restante*moneda[0]- parseFloat(datosFormulario.montoPagar))" class="form-control">
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">{{ moneda[1] }}</span>
  </div>
</div>
                                </div>

                            </div>

                            <div class="form-group row">

                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Precio de la cuota</label>
                                    <div class="input-group mb-3">
  <input type="text" class="form-control"  disabled :value="cuota.precio_cuota*moneda[0]" >
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">{{ moneda[1] }}</span>
  </div>
</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Monto a pagar
                                        
                                        </label>
                                        <div class="input-group mb-3">
                                            <input type="number" v-model="datosFormulario.montoPagar" class="form-control"
                                        placeholder="Ej. 20" 
                                        >
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">{{ moneda[1] }}</span>
  </div>
</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="" class="font-weight-bold">Forma de pago</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option disabled>Selecciona la forma de pago</option>
                                        <option selected value="1">Efectivo</option>
                                        <option value="2">Cheque</option>

                                        <option value="2">QR</option>
                                        <option value="3">Deposito</option>
                                    </select>
                                </div>
                           

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">No</button>
                            <button type="submit" class="btn btn-success" @click="registrarPagoCuota">Si, abonar</button>
                        </div>
                </div>
            </div>
            <!-- /.modal-dialog -->
        </div>
  </template>
  
  <script>
  export default {
    props: {
      cuota: {
        type: Object,
        required: true
      },
      ventaCredito: {
        type: Object,
        required: true
      },
      modal: {
        type: Number,
        required: true
      },
      arrayCuotas: {
            type: Array,
            required: true
        },
       moneda: {
            type: Array,
            required: true
        }
      

    },
    data() {
      return {
        datosFormulario: {
          montoPagar: 0
        },
        errores: {},
        ultimaCuota:0,
      };
    },
    methods: {
        registrarPagoCuota() {
            console.log("Cuota actual",this.cuota);
            console.log("Array de cuotas",this.arrayCuotas)
            const posicionCuotaActual = this.arrayCuotas.findIndex(cuota => cuota.id === cuota.id);
            if (this.cuota.numero_cuota>1){
            this.ultimaCuota=this.arrayCuotas[this.cuota.numero_cuota-2].saldo_restante;

            }else{
                this.ultimaCuota=this.cuota.saldo_restante;
            }
      const idCredito = this.cuota.idcredito;
      const numeroCuota = this.cuota.numero_cuota;
      const montoPago = this.cuota.precio_cuota;
      if ((parseFloat(this.datosFormulario.montoPagar)/parseFloat(this.moneda[0])).toFixed(2)!=this.cuota.precio_cuota){
        this.toastError("El monto a pagar debe ser igual al precio de la cuota")
        return 
      }
      const data = {
        id_credito: idCredito,
        numero_cuota: numeroCuota,
        monto_pago: montoPago,
        cuota_anterior:this.ultimaCuota
      };
      axios.post('/credito/cuotas/registrar', data)
        .then(response => {
          // Manejar la respuesta del servidor
          this.toastSuccess('Pago de cuota registrado correctamente');
          this.ventaCredito.total-=montoPago;
          this.cerrarModal();
        })
        .catch(error => {
          // Manejar errores
          console.error(error);
          alert('Hubo un error al procesar la solicitud. Inténtalo de nuevo más tarde.');
        });
    },
      cerrarModal() {
        this.datosFormulario= {
                montoPagar:0,
            };
        this.$emit('cerrar-modal'); 
        // Lógica para cerrar el modal
      },
      formatFecha(fechaOriginal) {
            const fecha = new Date(fechaOriginal);
            const dia = fecha.getDate();
            const mes = fecha.getMonth() + 1;
            const anio = fecha.getFullYear();

            const diaFormateado = dia < 10 ? `0${dia}` : dia;
            const mesFormateado = mes < 10 ? `0${mes}` : mes;

            return `${diaFormateado}-${mesFormateado}-${anio}`;
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
        },
    }
  };
  </script>
  