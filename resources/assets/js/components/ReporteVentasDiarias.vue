<template>
    <main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Reporte de Ventas Diarias
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <input type="date" v-model="fecha" class="form-control" />
                        <button type="submit" @click="generarReporte" class="btn btn-primary">
                            <i class="fa fa-search"></i> Generar Reporte
                        </button>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Número Factura</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="venta in arrayVentas" :key="venta.id">
                            <td v-text="venta.cliente"></td>
                            <td v-text="venta.total"></td>
                            <td v-text="venta.num_comprobante"></td>
                            <td v-text="venta.usuario"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right">
                    <strong>Total Ganado: </strong>Bs. {{ totalGanado }} 
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button @click="exportarPDF" class="btn btn-danger">Exportar a PDF</button>
                    <button @click="exportarExcel" class="btn btn-success">Exportar a Excel</button>
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
</main>
</template>

<script>

import jsPDF from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx-js-style';

export default {
data (){
    return {
        rol_id: 0,
        nombre : '',
        descripcion : '',
        fecha: '',
        arrayVentas: [],
        totalGanado: 0,
        modal : 0,
        tituloModal : '',
        tipoAccion : 0,
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
        buscar : ''
    }
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
    generarReporte() {
            let me = this;
            var url = '/ventas-diarias?fecha=' + me.fecha;
            axios.get(url)
                .then(function (response) {
                    if ('mensaje' in response.data && response.data.mensaje === 'Ninguna Venta Realizada en la Fecha Indicada') {
                    swal("Ninguna Venta", "No se realizaron ventas en la fecha seleccionada", "info");
                    }else{
                        var respuesta = response.data;
                        me.arrayVentas = respuesta.ventas;
                        me.totalGanado = respuesta.totalGanado;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
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

    const columns = ['Cliente', 'Monto', 'Número Factura', 'Usuario'];
    const rows = this.arrayVentas.map(venta => [venta.cliente, venta.total, venta.num_comprobante, venta.usuario]);
    pdf.autoTable({ head: [columns], body: rows, startY: tableYPosition });

    pdf.setFont('bold');
    const totalGanado = `Total Ganado: Bs. ${this.totalGanado}`;
    pdf.text(totalGanado, 15, pdf.autoTable.previous.finalY + 10);
    pdf.setFont('normal');

    pdf.save('reporte_ventas_diarias.pdf');
    },

    exportarExcel() {
    const workbook = XLSX.utils.book_new();

    const worksheet = XLSX.utils.aoa_to_sheet([]);

    const startRow = 5;

    worksheet['!merges'] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: this.arrayVentas.length } }];
    worksheet['A1'] = { t: 's', v: 'REPORTE DE VENTAS DIARIAS', s: { 
        font: { sz: 16, bold: true, color: { rgb: 'FFFFFF' } },
        alignment: { horizontal: 'center', vertical: 'center' },
        fill: { fgColor: { rgb: '3669a8' } } } };


    const fechaStyle = { font: { bold: true, color: { rgb: '000000' } }, border: { top: { style: 'thin', color: { auto: 1 } }, right: { style: 'thin', color: { auto: 1 } }, bottom: { style: 'thin', color: { auto: 1 } }, left: { style: 'thin', color: { auto: 1 } } } };
    worksheet['A2'] = { t: 's', v: `Fecha: ${this.fecha}`, s: fechaStyle };

    worksheet['!merges'].push({ s: { r: 1, c: 0 }, e: { r: 2, c: this.arrayVentas.length } });

    const headerStyle = { font: { bold: true, color: { rgb: 'FFFFFF' } }, fill: { fgColor: { rgb: '3669a8' } } };
    const headers = ['Cliente', 'Monto', 'Número Factura', 'Usuario'];

    headers.forEach((header, index) => {
        worksheet[XLSX.utils.encode_cell({ r: 3, c: index })] = { t: 's', v: header, s: headerStyle };
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

    




    cambiarPagina(page,buscar,criterio){
        let me = this;
        //Actualiza la página actual
        me.pagination.current_page = page;
        //Envia la petición para visualizar la data de esa página
        me.listarRol(page,buscar,criterio);
    }
},
mounted() {
    
}
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
