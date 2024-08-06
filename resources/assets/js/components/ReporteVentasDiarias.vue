<template>
   <main class="main">
        
    <Panel>
        <Toast :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }" style="padding-top: 40px;">
                </Toast>
                <template #header>
                    <div class="panel-header">
                        
                        <h4 class="panel-icon">Reporte de Ventas</h4>
                    </div>
                </template>
                <template >
                    <div v-if="listado === 1">
                        <template>
                            <div class="p-grid p-mb-3">
                                <div class="p-col-12 p-md-3 px-md-2">
                                    <b-form-datepicker v-model="fecha" locale="es"
                                        class="form-control"></b-form-datepicker>
                                </div>
                                <div class="p-col-12 p-md-3 px-md-2">
                                    <Dropdown v-model="idcategoria" :options="categoriasOptions" optionLabel="nombre"
                                        optionValue="id" placeholder="Seleccione" class="w-full" />
                                </div>
                                <div class="p-col-12 p-md-3 px-md-2">
                                    <Dropdown v-model="idUsuario" :options="usuariosOptions" optionLabel="usuario"
                                        optionValue="id" placeholder="Seleccione Usuario" class="w-full" />
                                </div>
                                <div class="p-col-12 p-md-3">
                                    <Button label="Generar Reporte" icon="pi pi-search" @click="generarReporte" />
                                </div>
                            </div>
                        </template>

                        <DataTable :value="arrayVentas" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 20]"
                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                            <Column>
                                <template #body="slotProps">
                                    <Button icon="pi pi-eye" class="p-button-success p-button-sm"
                                        @click="verVenta(slotProps.data.id)" />
                                </template>
                            </Column>
                            <Column field="cliente" header="Cliente"></Column>
                            <Column field="articulo" header="Producto"></Column>
                            <Column field="cantidad" header="Cantidad"></Column>
                            <Column field="precio" header="Precio"></Column>
                            <Column field="total" header="Total"></Column>
                            <Column field="num_comprobante" header="Número Factura"></Column>
                        </DataTable>

                        <div class="p-d-flex p-jc-between p-ai-center p-mt-3">
                            <div>
                                <strong>Total Ganado: </strong>Bs. {{ totalGanado }}
                            </div>
                            <div>
                                <Button label="Exportar a PDF" icon="pi pi-file-pdf" class="p-button-danger p-mr-2"
                                    @click="exportarPDF" />
                                <Button label="Exportar a Excel" icon="pi pi-file-excel" class="p-button-success p-mr-2"
                                    @click="exportarExcel" />
                                <Button label="Exportar a Whatsapp" icon="pi pi-whatsapp" class="p-button-success"
                                    @click="exportarWhatsapp" />
                            </div>
                        </div>
                    </div>

                    <div v-if="listado === 2">
                        <div class="p-grid p-mb-3">
                            <div class="p-col-12 p-md-9">
                                <div class="p-field">
                                    <label>Cliente</label>
                                    <InputText v-model="cliente" disabled />
                                </div>
                            </div>
                            <div class="p-col-12 p-md-3">
                                <div class="p-field">
                                    <label>Impuesto</label>
                                    <InputText v-model="impuesto" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="p-grid p-mb-3">
                            <div class="p-col-12 p-md-4">
                                <div class="p-field">
                                    <label>Tipo Comprobante</label>
                                    <InputText v-model="tipo_comprobante" disabled />
                                </div>
                            </div>
                            <div class="p-col-12 p-md-4">
                                <div class="p-field">
                                    <label>Serie Comprobante</label>
                                    <InputText v-model="serie_comprobante" disabled />
                                </div>
                            </div>
                            <div class="p-col-12 p-md-4">
                                <div class="p-field">
                                    <label>Número Comprobante</label>
                                    <InputText v-model="num_comprobante" disabled />
                                </div>
                            </div>
                        </div>

                        <DataTable :value="arrayDetalle">
                            <Column field="articulo" header="Artículo"></Column>
                            <Column field="precio" header="Precio"></Column>
                            <Column field="cantidad" header="Cantidad"></Column>
                            <Column field="descuento" header="Descuento"></Column>
                            <Column field="subtotal" header="Subtotal">
                                <template #body="slotProps">
                                    {{ (slotProps.data.precio * slotProps.data.cantidad -
                                    slotProps.data.descuento).toFixed(2) }}
                                </template>
                            </Column>
                        </DataTable>

                        <div class="p-text-right p-mt-3">
                            <strong>Total: ${{ total.toFixed(2) }}</strong>
                        </div>

                        <div class="p-mt-3">
                            <Button label="Cerrar" icon="pi pi-times" @click="ocultarDetalle" />
                        </div>
                    </div>
                </template>
            </Panel>
       
    </main>
</template>

<script>
import axios from 'axios';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx-js-style';
import Card from 'primevue/card';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Panel from 'primevue/panel';
import Toast from 'primevue/toast';
export default {
    components: {
        Card,
        Calendar,
        Dropdown,
        Button,
        DataTable,
        Column,
        InputText,
        Panel,
        Toast,
    },
    data() {
        return {
            arrayVentas: [],
      total: 0,
            arrayCategoria: [
                { id: '1', nombre: 'Categoría 1' },
                { id: '2', nombre: 'Categoría 2' },
                // Agrega más categorías según sea necesario
            ],
            arrayUsuarios: [
                { id: '1', usuario: 'Usuario 1' },
                { id: '2', usuario: 'Usuario 2' },
                // Agrega más usuarios según sea necesario
            ],
            categoryOptions: [
                { label: 'Todas las categorías', value: 'all' }
            ],
            userOptions: [
                { label: 'Todos los usuarios', value: 'all' }
            ],
            telefonoEmpresa: 0,
            idUsuario: 'all',
            arrayUsuarios: [],
            fecha: new Date().toISOString().split('T')[0],
            cliente: "",
            num_comprobante: "",
            impuesto: "",
            tipo_comprobante: "",
            serie_comprobante: "",
            total: null,
            listado: 1,
            arrayVentas: [],
            arrayCategoria: [],
            arrayDetalle: [],
            idcategoria: 'all',
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
            },
            offset: 3,
            criterio: 'nombre',
            buscar: ''
        };
    },
    computed: {
        categoriasOptions() {
            return [
                { id: '0', nombre: 'Seleccione' },
                { id: 'all', nombre: 'Todas las categorías' },
                ...this.arrayCategoria
            ];
        },
        usuariosOptions() {
            return [
                { id: '0', usuario: 'Seleccione Usuario' },
                { id: 'all', usuario: 'Todos los usuarios' },
                ...this.arrayUsuarios
            ];
        },
        isActived() {
            return this.pagination.current_page;
        },
        totalGanado() {
      return this.arrayVentas.reduce((total, venta) => {
        const ventaTotal = parseFloat(venta.total) || 0;
        return total + ventaTotal;
      }, 0).toFixed(2);
    },
        pagesNumber() {
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
        selectUsuarios() {
            let me = this;
            var url = '/usuario/selectUsuario';
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayUsuarios = respuesta.usuarios;
            }).catch(function (error) {
                console.log(error);
            });
        }, onContext(ctx) {
            this.context = ctx
        },
        onContext(ctx) {
            this.context = ctx
        },
        cambiarPagina(page) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envía la petición para visualizar la data de esa página
            me.listarRol(page, me.buscar, me.criterio);
        },
    
        calculateSubtotal(item) {
      const subtotal = (item.precio * item.cantidad) - item.descuento;
      return typeof subtotal === 'number' ? subtotal.toFixed(2) : '0.00';
    },
    ocultarDetalle() {
      this.listado = 1;
      this.cliente = '';
      this.tipo_comprobante = '';
      this.serie_comprobante = '';
      this.num_comprobante = '';
      this.impuesto = '';
      this.total = 0;
      this.arrayDetalle = [];
    },
    verVenta(id) {
      this.listado = 2;
      axios.get(`/venta/obtenerCabecera?id=${id}`)
        .then(response => {
          const venta = response.data.venta[0];
          this.cliente = venta.nombre;
          this.tipo_comprobante = venta.tipo_comprobante;
          this.serie_comprobante = venta.serie_comprobante;
          this.num_comprobante = venta.num_comprobante;
          this.impuesto = venta.impuesto;
          this.total = parseFloat(venta.total) || 0;
        })
        .catch(error => {
          console.log(error);
        });

      axios.get(`/venta/obtenerDetalles?id=${id}`)
        .then(response => {
          this.arrayDetalle = response.data.detalles;
        })
        .catch(error => {
          console.log(error);
        });
    },

    generarReporte() {
      const url = `/ventas-diarias?fecha=${this.fecha}&idCategoria=${this.idcategoria}&idUsuario=${this.idUsuario}`;

      axios.get(url)
        .then(response => {
          if ('mensaje' in response.data && response.data.mensaje === 'Ninguna Venta Realizada en la Fecha Indicada') {
            this.$swal("Ninguna Venta", "No se realizaron ventas en la fecha seleccionada", "info");
          } else {
            this.arrayVentas = response.data.ventas;
          }
        })
        .catch(error => {
          console.log(error);
        });
    },



        selectCategoria() {
            let me = this;
            var url = '/categoria/selectCategoria';
            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayCategoria = respuesta.categorias;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },
        exportarExcel() {
            const workbook = XLSX.utils.book_new();

            const worksheet = XLSX.utils.aoa_to_sheet([]);

            const startRow = 5;

            worksheet['!merges'] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: 3 } }];
            worksheet['A1'] = {
                t: 's', v: 'REPORTE DE VENTAS DIARIAS', s: {
                    font: { sz: 16, bold: true, color: { rgb: 'FFFFFF' } },
                    alignment: { horizontal: 'center', vertical: 'center' },
                    fill: { fgColor: { rgb: '3669a8' } }
                }
            };


            const fechaStyle = { font: { bold: true, color: { rgb: '000000' } }, border: { top: { style: 'thin', color: { auto: 1 } }, right: { style: 'thin', color: { auto: 1 } }, bottom: { style: 'thin', color: { auto: 1 } }, left: { style: 'thin', color: { auto: 1 } } } };
            worksheet['A2'] = { t: 's', v: `Fecha: ${this.fecha}`, s: fechaStyle };

            worksheet['!merges'].push({ s: { r: 1, c: 0 }, e: { r: 2, c: 3 } });

            const headerStyle = { font: { bold: true, color: { rgb: 'FFFFFF' } }, fill: { fgColor: { rgb: '3669a8' } } };
            const headers = ['Cliente', 'Monto', 'Número Factura', 'Usuario'];

            headers.forEach((header, index) => {
                worksheet[XLSX.utils.encode_cell({ r: 3, c: 3 })] = { t: 's', v: header, s: headerStyle };
            });

            for (let i = 0; i < this.arrayVentas.length; i++) {
                const rowData = Object.values(this.arrayVentas[i]);
                XLSX.utils.sheet_add_aoa(worksheet, [rowData], { origin: `A${startRow + i}` });
            }

            const totalGanado = this.totalGanado;

            const totalRow = [`Total Ganado: Bs. ${totalGanado}`];

            worksheet['!merges'].push({ s: { r: startRow + this.arrayVentas.length, c: 0 }, e: { r: startRow + this.arrayVentas.length, c: 3 } });

            XLSX.utils.sheet_add_aoa(worksheet, [totalRow], {
                origin: `A${startRow + this.arrayVentas.length + 1}`
            });

            const columnWidths = [
                { wch: 27.8 },
                { wch: 16.0 },
                { wch: 18.6 },
                { wch: 15.2 }
            ];
            worksheet['!cols'] = columnWidths;

            XLSX.utils.book_append_sheet(workbook, worksheet, 'Reporte Ventas');

            XLSX.writeFile(workbook, 'reporte_ventas_diarias.xlsx');
        },


        exportarPDF() {
            const pdf = new jsPDF();

            pdf.setFont('bold');
            const titulo = 'Reporte de Ventas Diarias';
            const fecha = `Fecha: ${this.fecha}`;
            pdf.setFont('normal');

            const pageSize = pdf.internal.pageSize;
            const pageWidth = pageSize.width;
            const textWidth = pdf.getStringUnitWidth(titulo) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;

            const xPosition = (pageWidth - textWidth) / 2;

            pdf.text(titulo, xPosition, 10);
            pdf.text(fecha, 15, 20);

            const tableYPosition = 30;

            const columns = ['Cliente', 'Producto', 'Cantidad', 'Precio', 'Total', 'Número Factura'];
            const rows = this.arrayVentas.map(venta => [venta.cliente, venta.articulo, venta.cantidad, venta.precio, (venta.precio * venta.cantidad), venta.num_comprobante]);
            pdf.autoTable({ head: [columns], body: rows, startY: tableYPosition });

            pdf.setFont('bold');
            const totalGanado = `Total Ganado: Bs. ${this.totalGanado}`;
            pdf.text(totalGanado, 15, pdf.autoTable.previous.finalY + 10);
            pdf.setFont('normal');

            pdf.save('reporte_ventas_diarias.pdf');
        },

        exportarWhatsapp() {
            const data = {
                telefono: this.telefonoEmpresa,
                fecha: this.fecha,
                ventas: this.arrayVentas,
                totalGanado: this.totalGanado
            };
            axios.post('/enviarWhatsapp', data)
                .then(function (response) {
                    if (response.data.error) {
                        swal("Error", response.data.error.error_data.details, "error");
                    } else {
                        swal("Éxito", "Reporte enviado a WhatsApp con éxito.", "success");
                    }
                })
                .catch(function (error) {
                    console.error('Error:', error);
                    alert('Ocurrió un error al enviar el reporte.');
                });
        },
        datosEmpresa() {
            let me = this;
            var url = '/empresa';

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta);
                me.telefonoEmpresa = respuesta.empresa.telefono;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },



        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarRol(page, buscar, criterio);
        }
    },
    mounted() {
        this.selectCategoria();
        this.generarReporte();
        this.selectUsuarios();
        this.datosEmpresa();
    }
}
</script>
<style scoped>
>>> .p-panel-header {
    padding: 0.75rem;
}
.panel-header {
    display: flex;
    align-items: center;
}

.panel-icon {
    font-size: 2rem;
    padding-left: 10px;
}

.panel-icon {
    font-size: 1.5rem;
    margin: 0;
}

</style>