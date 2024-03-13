<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="/">Escritorio</a> </li>
        </ol>
        <div class="container-fluid">
        
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Kardex Clientes Detallado Global

                    <button @click="" class="btn btn-primary" data-toggle="modal" data-target="#modalFiltros">
                        <i class="fa fa-search"></i>&nbsp;Filtros
                    </button>
                    <button @click="exportarExcel()" class="btn btn-success">
                        <i class="icon-doc"></i>&nbsp;Exportar a EXCEL
                    </button>
                    <button @click="exportarPDF()" class="btn btn-danger">
                        <i class="icon-doc"></i>&nbsp;Exportar a PDF
                    </button>
                </div>

                <br>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Num Comprobante</th>
                                        <th>Fecha</th>
                                        <th>Detalle</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Comprobante</th>
                                        <th>Precio Unitario</th>
                                        <th>Impuesto</th>
                                        <th>Ventas</th>
                                        <th>Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr  v-for="detalle in arrayDetalles" :key="arrayDetalles.idventa">
                                        <td>{{ detalle.num_comprobante }}</td>
                                        <td>{{ detalle.fecha_hora }}</td>
                                        <td>{{ detalle.nombre_generico }}</td>
                                        <td>{{ detalle.fecha_vencimiento }}</td>
                                        <td>{{ detalle.tipo_comprobante }}</td>
                                        <td>{{ detalle.precio_costo_unid }}</td>
                                        <td>{{ detalle.impuesto }}</td>
                                        <td>{{ detalle.total }}</td>
                                        <td>{{ 0 }}</td>



                                        
                                    </tr>
                                </tbody>
                        </table>

                        <hr>
                        
                    </div>   
                </div>
            </div>
        </div>


        <div class="modal fade" id="modalFiltros" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">Filtros de reportes</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">VENDEDORES: <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="vendedorId" @change="">
                                            <option value="0" disabled>Seleccione</option>
                                            <option value="todos">TODOS</option>
                                            <option v-for="opcion in arrayEjecutivos" :key="opcion.id" :value="opcion.id">{{ opcion.nombre }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">SUCURSAL: <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="sucursalId" @change="">
                                            <option value="0" disabled>Seleccione</option>
                                            <option value="todos">TODOS</option>
                                            <option v-for="opcion in arraySucursales" :key="opcion.id" :value="opcion.id">{{ opcion.nombre }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">CLIENTES: <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="clienteId" @change="">
                                            <option value="0" disabled>Seleccione</option>
                                            <option value="todos">TODOS</option>
                                            <option v-for="opcion in clientes" :key="opcion.id" :value="opcion.id">{{ opcion.nombre }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">TIPO COMPROBANTE: <span class="text-danger">*</span></label>
                                        <select class="form-control" v-model="tipo_comprobante" @change="">
                                            <option value="0" disabled>Seleccione</option>
                                            <option value="todos">TODOS</option>
                                            <option value="FACTURA">Factura</option>
                                            <option value="BOLETA">Boleta</option>
                                            <option value="TICKET">Ticket</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fechaInicio" class="font-weight-bold">FECHA INICIO: <span class="text-danger">*</span> </label>
                                    <input type="date" id="fechaInicio" v-model="fechaInicio" @change="" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="fechaFin" class="font-weight-bold">FECHA FIN: <span class="text-danger">*</span></label>
                                    <input type="date" id="fechaFin" v-model="fechaFin" @change="" class="form-control">
                                </div>
                            </div>
                            
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="generarReporte()" data-dismiss="modal"    >Generar Reporte</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx-js-style';

export default {
data() {
    return {
        pagination: {
            'total': 0,
            'current_page': 0,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0,
        },
        offset: 3,
        currentPage: 1,
        
        arraySucursales : [],
        arrayEjecutivos : [],
        arrayDetalles: [],

        clientes: [],
        vendedorId: null,
        sucursalId: null,
        clienteId: null,
        tipo_comprobante: null,
        fechaInicio: '2024-01-01',
        fechaFin: '2024-12-30',
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
    },
},
methods: {        
    selectSucursal() {
        let me = this;
        var url = '/sucursal/selectSucursal';
        axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arraySucursales = respuesta.sucursales;
            console.log('SUCURSAL',me.arraySucursales);
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    selectEjecutivos() {
        let me = this;
        var url = '/user/selectUser/rol?filtro=' + 2;
        axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayEjecutivos = respuesta.usuarios;
            console.log('Ejecutivos',me.arrayEjecutivos);
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    selectCliente() {
        let me = this;
        var url = '/cliente/selectCliente';
        axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.clientes = respuesta.clientes;
            console.log('CLIENTES',me.clientes);
        })
        .catch(function (error) {
            console.log(error);
        });
    },
    generarReporte() {
        let me = this;
        
        if (me.vendedorId == null) {
            me.vendedorId = 'todos';
        }
        
        if (me.sucursalId == null) {
            me.sucursalId = 'todos';
        }

        if (me.clienteId == null) {
            me.clienteId = 'todos';
        }

        if (me.tipo_comprobante == null) {
            me.tipo_comprobante = 'todos';
        }

        console.log('vendedorId:', me.vendedorId);
        console.log('sucursalId:', me.sucursalId);
        console.log('clienteId:', me.clienteId);
        console.log('tipo_comprobante:', me.tipo_comprobante);

        var url ='/kardex-clientes-detallado-global?vendedorId=' + this.vendedorId + '&sucursalId=' + this.sucursalId + '&clienteId=' + this.clienteId + '&tipo_comprobante=' + this.tipo_comprobante + '&fechaInicio=' + this.fechaInicio  + '&fechaFin=' + this.fechaFin;
                
        axios.get(url).then(function (response) {
            var respuesta = response.data;
            me.arrayDetalles = respuesta.detalles;

            console.log('DETALLES: ', me.arrayDetalles);
        })
        .catch(function (error) {
            console.log(error);
        });
    },


    exportarExcel() {
        const workbook = XLSX.utils.book_new();
        const worksheet = XLSX.utils.aoa_to_sheet([]);

        const startRow = 6;

        worksheet['!merges'] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: 3 } }];
        worksheet['A1'] = { t: 's', v: 'KARDEX CLIENTES DETALLADO GLOBAL', s: { 
            font: { sz: 16, bold: true, color: { rgb: 'FFFFFF' } },
            alignment: { horizontal: 'center', vertical: 'center' },
            fill: { fgColor: { rgb: '3669a8' } } } };

        const fechaStyle = { font: { bold: true, color: { rgb: '000000' } }, border: { top: { style: 'thin', color: { auto: 1 } }, right: { style: 'thin', color: { auto: 1 } }, bottom: { style: 'thin', color: { auto: 1 } }, left: { style: 'thin', color: { auto: 1 } } } };
        worksheet['A2'] = { t: 's', v: `Fecha: ${new Date().toLocaleDateString('es-ES', { year: 'numeric', month: 'numeric', day: 'numeric' })}`, s: fechaStyle };

        const headers = ['Num Comprobante', 'Fecha Hora', 'Detalle', 'Fecha Vencimiento', 'Tipo Comprobante', 'Costo Unitario', 'Impuesto', 'Ventas'];
        const headerStyle = { font: { bold: true, color: { rgb: 'FFFFFF' } }, fill: { fgColor: { rgb: '3669a8' } } };

        headers.forEach((header, index) => {
            worksheet[XLSX.utils.encode_cell({ r: 4, c: index })] = { t: 's', v: header, s: headerStyle };
        });

        for (let i = 0; i < this.arrayDetalles.length; i++) {
            const { num_comprobante, fecha_hora, nombre_generico, fecha_vencimiento, tipo_comprobante, precio_costo_unid, impuesto, total } = this.arrayDetalles[i];
            const rowData = [num_comprobante, fecha_hora, nombre_generico, fecha_vencimiento, tipo_comprobante, precio_costo_unid, impuesto, total ];
            XLSX.utils.sheet_add_aoa(worksheet, [rowData], { origin: `A${startRow + i}` });
        }

        const columnWidths = [
            { wch: 25 },
            { wch: 25 },
            { wch: 20 },
            { wch: 12 },
            { wch: 10 },
            { wch: 12 },
            { wch: 15 },
            { wch: 15 }
        ];
        worksheet['!cols'] = columnWidths;

        XLSX.utils.book_append_sheet(workbook, worksheet, 'kardex clientes detallado');

        XLSX.writeFile(workbook, 'kardex_clientes_detallado_global.xlsx');
    },

    exportarPDF() {
        const pdf = new jsPDF();

        pdf.setFont('helvetica');
        const titulo = 'Kardex Clientes Detallado Global';
        const fecha = `Fecha: ${new Date().toLocaleDateString('es-ES', { year: 'numeric', month: 'numeric', day: 'numeric' })}`;
        pdf.setFont('helvetica');

        const pageSize = pdf.internal.pageSize;
        const pageWidth = pageSize.width;
        const textWidth = pdf.getStringUnitWidth(titulo) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;

        const xPosition = (pageWidth - textWidth) / 2;

        pdf.text(titulo, xPosition, 10);
        pdf.text(fecha, 15, 20);

        const tableYPosition = 30;

        const columns = ['Num Comprobante', 'Fecha Hora', 'Detalle', 'Fecha Vencimiento', 'Tipo Comprobante', 'Costo Unitario', 'Impuesto', 'Ventas'];
        const rows = this.arrayDetalles.map(datos => [datos.num_comprobante, datos.fecha_hora, datos.nombre_generico, datos.fecha_vencimiento, datos.tipo_comprobante, datos.precio_costo_unid, datos.impuesto, datos.total]);
        pdf.autoTable({ head: [columns], body: rows, startY: tableYPosition });

        pdf.setFont('helvetica');
        pdf.save('kardex_clientes_detallado_global.pdf');
    }
},
mounted() {
        this.selectSucursal();
        this.selectEjecutivos();
        this.selectCliente();
    }
}
</script>