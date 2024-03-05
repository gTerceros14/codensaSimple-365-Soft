<?php

namespace App\Http\Controllers;

use App\Moneda;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesVentas extends Controller
{
    public function ResumenVentasPorDocumento(Request $request){
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->join('tipo_ventas','ventas.idtipo_venta','=','tipo_ventas.id')
        ->join('roles','users.idrol','=','roles.id')
        ->join('sucursales','users.idsucursal','=','sucursales.id')
        ->join('monedas', 'monedas.id', '=', DB::raw('1'))
        ->select('ventas.num_comprobante as Factura',
                'ventas.id',
                'sucursales.nombre as Nombre_sucursal',
                'ventas.fecha_hora',
                'monedas.tipo_cambio as Tipo_Cambio',
                'tipo_ventas.nombre_tipo_ventas as Tipo_venta',
                'roles.nombre AS nombre_rol',
                'users.usuario',
                'personas.nombre',
                'ventas.total AS importe_BS',
                DB::raw('ROUND(ventas.total / monedas.tipo_cambio,2) AS importe_usd'))
        ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);

        if ($request->has('estadoVenta')) {
            $estado_venta = $request->estadoVenta;
            if ($estado_venta !== 'Todos') {
                $ventas->where('ventas.estado', '=', $estado_venta);
            }
        }
        
        if ($request->has('sucursal') && $request->sucursal !== 'undefined') {
            $sucursal = $request->sucursal;
            $ventas->where('sucursales.id', $sucursal);
        }

        if ($request->has('ejecutivoCuentas') && $request->ejecutivoCuentas !== 'undefined') {
            $ejecutivoCuentas = $request->ejecutivoCuentas;
            $ventas->where('ventas.idusuario' , $ejecutivoCuentas);
        }

        if ($request->has('idcliente') && $request->idcliente !== 'undefined') {
            $cliente = $request->idcliente;
            $ventas->where('ventas.idcliente' , $cliente);
        }


        $ventas = $ventas->get();

        $total_importeBs =0;
        $total_importeUSD = 0;
        foreach ($ventas as &$venta){
            $total_importeBs += $venta->importe_BS;
            $total_importeUSD += $venta->importe_usd;
        }
        return['ventas' => $ventas,
                'total_BS'=>$total_importeBs,
                'total_USD'=>$total_importeUSD];

    }
    public function ventasPorProducto(Request $request){
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        $ventas = Venta::join('detalle_ventas','ventas.id','detalle_ventas.idventa')
        ->join('personas','personas.id','=','ventas.idcliente')
        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->join('categorias','articulos.idcategoria','=','categorias.id')
        ->join('marcas','articulos.idmarca','=','marcas.id')
        ->join('industrias','articulos.idindustria','=','industrias.id')
        ->join('medidas','articulos.idmedida','=','medidas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->join('sucursales','users.idsucursal','=','sucursales.id')
        ->select('ventas.fecha_hora',
                'personas.nombre',
            'detalle_ventas.*',
            'articulos.codigo',
            'articulos.descripcion',
            'categorias.nombre as nombre_categoria',
            'marcas.nombre as nombre_marca',
            'industrias.nombre as nombre_industria',
            'medidas.descripcion_medida as medida')
        ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);

        if ($request->has('sucursal') && $request->sucursal !== 'undefined') {
                $sucursal = $request->sucursal;
                $ventas->where('sucursales.id', $sucursal);
            }

        if ($request->has('idcliente') && $request->idcliente !== 'undefined') {
                $cliente = $request->idcliente;
                $ventas->where('ventas.idcliente' , $cliente);
            }
        if ($request->has('articulo') && $request->articulo !== 'undefined') {
                $articulo = $request->articulo;
                $ventas->where('detalle_ventas.idarticulo' , $articulo);
            }
        if ($request->has('marca') && $request->marca !== 'undefined') {
                $idmarca = $request->marca;
                $ventas->where('articulos.idmarca' , $idmarca);
                
            }
        if ($request->has('linea') && $request->linea !== 'undefined') {
                $idlinea = $request->linea;
                $ventas->where('articulos.idcategoria' , $idlinea);
                
            }
        if ($request->has('industria') && $request->industria !== 'undefined') {
                $idindustria = $request->industria;
                $ventas->where('articulos.idindustria' , $idindustria);
                
            }
        $ventas = $ventas->get();
        return ['resultados' =>$ventas];
    }
    public function resumenFisicoMovimientos(Request $request){
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        /*$ventas = Venta::join('detalle_ventas','ventas.id','detalle_ventas.idventa')
        ->join('personas','personas.id','=','ventas.idcliente')
        ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->join('categorias','articulos.idcategoria','=','categorias.id')
        ->join('marcas','articulos.idmarca','=','marcas.id')
        ->join('industrias','articulos.idindustria','=','industrias.id')
        ->join('medidas','articulos.idmedida','=','medidas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->join('sucursales','users.idsucursal','=','sucursales.id')
        ->select('ventas.fecha_hora',
                'personas.nombre',
            'detalle_ventas.*',
            'articulos.codigo',
            'articulos.descripcion',
            'categorias.nombre as nombre_categoria',
            'marcas.nombre as nombre_marca',
            'industrias.nombre as nombre_industria',
            'medidas.descripcion_medida as medida')
        ->get();*/
        $ingresos = DB::table('ingresos')
        ->join('detalle_ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
        ->join('articulos','detalle_ingresos.idarticulo','=', 'articulos.id')
        ->join('almacens','almacens.encargado','=','ingresos.idusuario')
        ->join('sucursales','sucursales.id','=','almacens.sucursal')
        ->join('marcas','articulos.idmarca','=','marcas.id')
        ->join('categorias','articulos.idcategoria','=','categorias.id')
        ->select(DB::raw("'Ingreso' AS tipo"), 'articulos.codigo', 'detalle_ingresos.cantidad', 'articulos.descripcion', 'ingresos.fecha_hora','categorias.nombre as nombre_categoria','marcas.nombre as nombre_marca','detalle_ingresos.precio AS precio_ingreso')
        ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);
        

        $ventas = DB::table('ventas')
        ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
        ->join('articulos','detalle_ventas.idarticulo','=', 'articulos.id')
        ->join('almacens','almacens.encargado','=','ventas.idusuario')
        ->join('sucursales','sucursales.id','=','almacens.sucursal')
        ->join('marcas','articulos.idmarca','=','marcas.id')
        ->join('categorias','articulos.idcategoria','=','categorias.id')
        ->select(DB::raw("'Venta' AS tipo"), 'articulos.codigo',  'detalle_ventas.cantidad', 'articulos.descripcion', 'ventas.fecha_hora','categorias.nombre as nombre_categoria','marcas.nombre as nombre_marca','detalle_ventas.precio  AS precio_venta')
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
}
