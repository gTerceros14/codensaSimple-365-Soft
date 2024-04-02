<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="/">Escritorio</a></li>
        </ol>
    </main>
</template>


<script>
import vSelect from 'vue-select';

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

            arrayDetalles: [],
            vendedorId: 7,
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

        generarReporte() {
            console.log('generando reporte')
            var url = '/resumen-ventas-y-cobranzas?vendedorId=' + this.vendedorId
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    
                    me.arrayDetalles = respuesta.detalles;
                    console.log('DETALLES', me.arrayDetalles)
                })
                .catch(function (error) {
                    console.log(error);
                })

        }
        
    },
    mounted() {
        this.generarReporte()
    }
}
</script>
