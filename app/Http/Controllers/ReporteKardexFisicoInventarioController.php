<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteKardexFisicoInventarioController extends Controller
{
    public function generarReporte(Request $request) {
        // Obtener los parámetros de la solicitud
        $fechainicio = $request->fechainicio;
        $fechafin = $request->fechafin;
        $fechaInicioCompleta = $fechainicio . ' 00:00:00';
        $fechaFinCompleta = $fechafin . ' 23:59:59';
    
        // Iniciar las consultas
        $ingresos = DB::table('ingresos')
            ->join('detalle_ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
            ->join('articulos','detalle_ingresos.idarticulo','=', 'articulos.id')
            ->join('almacens','almacens.encargado','=','ingresos.idusuario')
            ->join('sucursales','sucursales.id','=','almacens.sucursal')
            ->select(DB::raw("'Ingreso' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ingresos.precio AS precio_ingreso')
            ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59']);
            
    
        $ventas = DB::table('ventas')
            ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
            ->join('articulos','detalle_ventas.idarticulo','=', 'articulos.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->join('sucursales','users.idsucursal','=','sucursales.id')
            ->select(DB::raw("'Venta' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante','detalle_ventas.precio  AS precio_venta')
            ->whereBetween('fecha_hora', [$fechainicio.' 00:00:00', $fechafin.' 23:59:59']);
            
    
        if ($request->has('articulo') && $request->articulo !== 'undefined') {
            $idarticulo = $request->articulo;
            $ingresos->where('articulos.id' , $idarticulo);
            $ventas->where('articulos.id' , $idarticulo);
        }
        if ($request->has('sucursal') && $request->sucursal !== 'undefined') {
            $sucursal = $request->sucursal;
            $ingresos->where('sucursales.id', $sucursal);
            $ventas->where('sucursales.id', $sucursal);
        }
        // Agregar filtros opcionales si se proporcionan otros parámetros
        if ($request->has('marca') && $request->marca !== 'undefined') {
            $idmarca = $request->marca;
            $ingresos->where('articulos.idmarca' , $idmarca);
            $ventas->where('articulos.idmarca' , $idmarca);
        }
        if ($request->has('linea') && $request->linea !== 'undefined') {
            $idlinea = $request->linea;
            $ingresos->where('articulos.idcategoria' , $idlinea);
            $ventas->where('articulos.idcategoria' , $idlinea);
        }
        if ($request->has('industria') && $request->industria !== 'undefined') {
            $idindustria = $request->industria;
            $ingresos->where('articulos.idindustria' , $idindustria);
            $ventas->where('articulos.idindustria' , $idindustria);
        }
        if ($request->has('grupo') && $request->grupo !== 'undefined') {
            $idgrupo = $request->grupo;
            $ingresos->where('articulos.idgrupo' , $idgrupo);
            $ventas->where('articulos.idgrupo' , $idgrupo);
        }
    
        // Realizar las consultas
        $ingresos = $ingresos->get();
        $ventas = $ventas->get();
    
        // Combinar los resultados de ingresos y ventas
        $resultados = $ingresos->concat($ventas)->sortBy('fecha_hora');
    
        // Calcular el saldo
        $saldo = 0;
        $saldoFisico = 0;
    
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
        $total_saldo = $saldoFisico; // Total físico como saldo total
    
        return ['resultados' => $resultados, 'total_saldo' => $total_saldo];
    }
    
    

public function generarReporteFisico(Request $request)
{
    $fechaInicio = $request->fechaInicio;
    $fechaFin = $request->fechaFin;

    $ingresos = DB::table('ingresos')
        ->join('detalle_ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
        ->join('articulos','detalle_ingresos.idarticulo','=','articulos.id')
        ->join('almacens','almacens.encargado','=','ingresos.idusuario')
        ->join('sucursales','sucursales.id','=','almacens.sucursal')
        ->select(DB::raw("'Ingreso' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante')
        ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);
        
    $ventas = DB::table('ventas')
        ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->join('sucursales','users.idsucursal','=','sucursales.id')
        ->select(DB::raw("'Venta' AS tipo"), 'fecha_hora', 'cantidad', 'num_comprobante', 'tipo_comprobante')
        ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);
        
        if ($request->has('articulo') && $request->articulo !== 'undefined') {
            $idarticulo = $request->articulo;
            $ingresos->where('articulos.id' , $idarticulo);
            $ventas->where('articulos.id' , $idarticulo);
        }
        if ($request->has('sucursal') && $request->sucursal !== 'undefined') {
            $sucursal = $request->sucursal;
            $ingresos->where('sucursales.id', $sucursal);
            $ventas->where('sucursales.id', $sucursal);
        }
        // Agregar filtros opcionales si se proporcionan otros parámetros
        if ($request->has('marca') && $request->marca !== 'undefined') {
            $idmarca = $request->marca;
            $ingresos->where('articulos.idmarca' , $idmarca);
            $ventas->where('articulos.idmarca' , $idmarca);
        }
        if ($request->has('linea') && $request->linea !== 'undefined') {
            $idlinea = $request->linea;
            $ingresos->where('articulos.idcategoria' , $idlinea);
            $ventas->where('articulos.idcategoria' , $idlinea);
        }
        if ($request->has('industria') && $request->industria !== 'undefined') {
            $idindustria = $request->industria;
            $ingresos->where('articulos.idindustria' , $idindustria);
            $ventas->where('articulos.idindustria' , $idindustria);
        }
        if ($request->has('grupo') && $request->grupo !== 'undefined') {
            $idgrupo = $request->grupo;
            $ingresos->where('articulos.idgrupo' , $idgrupo);
            $ventas->where('articulos.idgrupo' , $idgrupo);
        }
    
    
      // Realizar las consultas
        $ingresos = $ingresos->get();
        $ventas = $ventas->get();
    
        // Combinar los resultados de ingresos y ventas
        $resultados = $ingresos->concat($ventas)->sortBy('fecha_hora');
    
        // Calcular el saldo
        $saldo = 0;
        $saldoFisico = 0;
    
        foreach ($resultados as &$resultado) {
            if ($resultado->tipo === 'Ingreso') {
                $saldoFisico += $resultado->cantidad;
            } else {
                $saldoFisico -= $resultado->cantidad;
            }
            $resultado->resultado_operacionFisico = $saldoFisico;
        }
        $total_saldo = $saldoFisico; // Total físico como saldo total
    
        return ['resultados' => $resultados, 'total_saldo' => $total_saldo];}

}
