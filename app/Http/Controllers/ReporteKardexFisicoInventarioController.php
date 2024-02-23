<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteKardexFisicoInventarioController extends Controller
{
    public function generarReporte(Request $request){
        $idarticulo = $request->articulo;
        $idmarca = $request->marca;
        $idlinea = $request->linea;
        $idindustria = $request->industria;
        $idgrupo = $request->grupo;
        $fechainicio = $request->fechainicio;
        $fechafin = $request->fechafin;
        $fechaInicioCompleta = $fechainicio . ' 00:00:00';
        $fechaFinCompleta = $fechafin . ' 23:59:59';
        if ($idarticulo!=''){
            $ingresos = DB::table('ingresos')
                ->join('detalle_ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
                ->join('articulos','detalle_ingresos.idarticulo','=', 'articulos.id')
                ->select(DB::raw("'Ingreso' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ingresos.precio AS precio_ingreso')
                ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59'])
                ->where('articulos.id' , $idarticulo);

            $ventas = DB::table('ventas')
                ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
                ->join('articulos','detalle_ventas.idarticulo','=', 'articulos.id')
                ->select(DB::raw("'Venta' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ventas.precio  AS precio_venta')
                ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59'])
                ->where('articulos.id' , $idarticulo);
            if($idmarca !=''){
                $ingresos = DB::table('ingresos')
                    ->join('detalle_ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
                    ->join('articulos','detalle_ingresos.idarticulo','=', 'articulos.id')
                    ->select(DB::raw("'Ingreso' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ingresos.precio AS precio_ingreso')
                    ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59'])
                    ->where('articulos.id' , $idarticulo)
                    ->where('articulos.idmarca' , $idmarca);

                $ventas = DB::table('ventas')
                    ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
                    ->join('articulos','detalle_ventas.idarticulo','=', 'articulos.id')
                    ->select(DB::raw("'Venta' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ventas.precio  AS precio_venta')
                    ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59'])
                    ->where('articulos.id' , $idarticulo)
                    ->where('articulos.idmarca' , $idmarca);
            }
            if ($idlinea !=''){
                $ingresos = DB::table('ingresos')
                    ->join('detalle_ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
                    ->join('articulos','detalle_ingresos.idarticulo','=', 'articulos.id')
                    ->select(DB::raw("'Ingreso' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ingresos.precio AS precio_ingreso')
                    ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59'])
                    ->where('articulos.id' , $idarticulo)
                    ->where('articulos.idmarca' , $idmarca)
                    ->where('articulos.idcategoria' , $idlinea);

                $ventas = DB::table('ventas')
                    ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
                    ->join('articulos','detalle_ventas.idarticulo','=', 'articulos.id')
                    ->select(DB::raw("'Venta' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ventas.precio  AS precio_venta')
                    ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59'])
                    ->where('articulos.id' , $idarticulo)
                    ->where('articulos.idmarca' , $idmarca)
                    ->where('articulos.idcategoria' , $idlinea);
            }
            if ($idindustria !=''){
                $ingresos = DB::table('ingresos')
                    ->join('detalle_ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
                    ->join('articulos','detalle_ingresos.idarticulo','=', 'articulos.id')
                    ->select(DB::raw("'Ingreso' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ingresos.precio AS precio_ingreso')
                    ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59'])
                    ->where('articulos.id' , $idarticulo)
                    ->where('articulos.idmarca' , $idmarca)
                    ->where('articulos.idcategoria' , $idlinea)
                    ->where('articulos.idindustria' , $idindustria);

                $ventas = DB::table('ventas')
                    ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
                    ->join('articulos','detalle_ventas.idarticulo','=', 'articulos.id')
                    ->select(DB::raw("'Venta' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ventas.precio  AS precio_venta')
                    ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59'])
                    ->where('articulos.id' , $idarticulo)
                    ->where('articulos.idmarca' , $idmarca)
                    ->where('articulos.idcategoria' , $idlinea)
                    ->where('articulos.idindustria' , $idindustria);
            }
            if ($idgrupo !=''){
                $ingresos = DB::table('ingresos')
                    ->join('detalle_ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
                    ->join('articulos','detalle_ingresos.idarticulo','=', 'articulos.id')
                    ->select(DB::raw("'Ingreso' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ingresos.precio AS precio_ingreso')
                    ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59'])
                    ->where('articulos.id' , $idarticulo)
                    ->where('articulos.idmarca' , $idmarca)
                    ->where('articulos.idcategoria' , $idlinea)
                    ->where('articulos.idindustria' , $idindustria)
                    ->where('articulos.idgrupo' , $idgrupo);

                $ventas = DB::table('ventas')
                    ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
                    ->join('articulos','detalle_ventas.idarticulo','=', 'articulos.id')
                    ->select(DB::raw("'Venta' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ventas.precio  AS precio_venta')
                    ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59'])
                    ->where('articulos.id' , $idarticulo)
                    ->where('articulos.idmarca' , $idmarca)
                    ->where('articulos.idcategoria' , $idlinea)
                    ->where('articulos.idindustria' , $idindustria)
                    ->where('articulos.idgrupo' , $idgrupo);
            }
        }
       

    
    
    // Realizar la consulta
    $ingresos = $ingresos->get();
    $ventas = $ventas->get();

    // Combinar los resultados de ingresos y ventas
    $resultados = $ingresos->concat($ventas)->sortBy('fecha_hora');

    // Calcular el saldo
    $saldo = 0;
    $saldoFisico = 0;
    /*foreach ($resultados as &$resultado) {
        if ($resultado->tipo === 'Ingreso') {
            $saldo += $resultado->cantidad;
        } else {
            $saldo -= $resultado->cantidad;
        }
        $resultado->resultado_operacion = $saldo;
    }*/

    foreach ($resultados as &$resultado) {
        if ($resultado->tipo === 'Ingreso') {
            $resultado->subtotal = $resultado->cantidad * $resultado->precio_ingreso;
            $saldo += $resultado->subtotal;
            $saldoFisico += $resultado->cantidad;
        } else {
            $resultado->subtotal = $resultado->cantidad * $resultado->precio_venta;
            $saldo -= $resultado->subtotal;
            $saldoFisico -= $resultado->cantidad;
        }
        $resultado->resultado_operacionValorado = $saldo;
        $resultado->resultado_operacionFisico = $saldoFisico;
    }
    $total_saldo = $resultado->resultado_operacionFisico;
    return ['resultados' => $resultados,
            'total_saldo' => $total_saldo
            ];
}

}
