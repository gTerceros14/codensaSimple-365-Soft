<?php

namespace App\Http\Controllers;

use App\DetalleVenta;
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
        ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
        ->orderBy('ventas.fecha_hora', 'asc');

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

        $fechaInicio = $fechaInicio . ' 00:00:00';
        $fechaFin = $fechaFin . ' 23:59:59';
        $productos = DB::table('articulos')
        ->select(
            'articulos.id',
            'articulos.nombre',
            'articulos.codigo',
            'articulos.descripcion',
            'categorias.nombre as nombre_categoria',
            'marcas.nombre as nombre_marca',
            'industrias.nombre as nombre_industria',
            'medidas.descripcion_medida as medida'
        )
        ->join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
        ->join('marcas', 'articulos.idmarca', '=', 'marcas.id')
        ->join('industrias', 'articulos.idindustria', '=', 'industrias.id')
        ->join('medidas', 'articulos.idmedida', '=', 'medidas.id')
        ->join('inventarios','inventarios.idarticulo','=','articulos.id')
        ->join('almacens','inventarios.idalmacen','=','almacens.id')
        ->join('sucursales','almacens.sucursal','=','sucursales.id')
        ->groupBy('articulos.id', 'articulos.nombre', 'articulos.codigo', 'articulos.descripcion', 'categorias.nombre', 'marcas.nombre', 'industrias.nombre', 'medidas.descripcion_medida');

        
        if ($request->has('articulo') && $request->articulo !== 'undefined') {
            $idarticulo = $request->articulo;
            $productos->where('articulos.id' , $idarticulo);
        }
        if ($request->has('sucursal') && $request->sucursal !== 'undefined') {
            $sucursal = $request->sucursal;
            $productos->where('sucursales.id', $sucursal);
        }
        // Agregar filtros opcionales si se proporcionan otros parámetros
        if ($request->has('marca') && $request->marca !== 'undefined') {
            $idmarca = $request->marca;
            $productos->where('articulos.idmarca' , $idmarca);
        }
        if ($request->has('linea') && $request->linea !== 'undefined') {
            $idlinea = $request->linea;
            $productos->where('articulos.idcategoria' , $idlinea);
        }
    $productos=$productos->get();
    
    $resultados = [];
    
    foreach ($productos as $producto) {
        
        $saldoAnterior = DB::table('detalle_ingresos')
            ->join('ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
            ->where('detalle_ingresos.idarticulo', $producto->id)
            ->where('ingresos.fecha_hora', '<', $fechaInicio)
            ->sum('detalle_ingresos.cantidad');
        
        $egresosAnteriores = DB::table('ventas')
            ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
            ->where('detalle_ventas.idarticulo', $producto->id)
            ->where('ventas.fecha_hora', '<', $fechaInicio)
            ->sum('detalle_ventas.cantidad');
        $saldoAnterior -= $egresosAnteriores;
    
        $ingresos = DB::table('detalle_ingresos')
            ->join('ingresos', 'detalle_ingresos.idingreso', '=', 'ingresos.id')
            ->where('detalle_ingresos.idarticulo', $producto->id)
            ->where('ingresos.fecha_hora', '>=', $fechaInicio)
            ->where('ingresos.fecha_hora', '<=', $fechaFin)
            ->sum('detalle_ingresos.cantidad');
    
        $ventas = DB::table('ventas')
            ->join('detalle_ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
            ->where('detalle_ventas.idarticulo', $producto->id)
            ->where('ventas.fecha_hora', '>=', $fechaInicio)
            ->where('ventas.fecha_hora', '<=', $fechaFin)
            ->sum('detalle_ventas.cantidad');
        
        $saldoActual = $saldoAnterior + $ingresos - $ventas;
    
        $resultado = [
            
            'nombre_producto' => $producto->nombre,
            'codigo' => $producto->codigo,
            'descripcion' => $producto->descripcion,
            'nombre_categoria' => $producto->nombre_categoria,
            'nombre_marca' => $producto->nombre_marca,
            'nombre_industria' => $producto->nombre_industria,
            'medida' => $producto->medida,
            'saldo_anterior' => $saldoAnterior,
            'ingresos' => $ingresos,
            'ventas' => $ventas,
            'saldo_actual' => $saldoActual
        ];
    
        $resultados[] = $resultado;
    }
    
        return ['resultados' => $resultados];
    
    }
    public function ResumenVentasPorDocumentoDetallado(Request $request){
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;
        $fechaInicio = $fechaInicio . ' 00:00:00';
        $fechaFin = $fechaFin . ' 23:59:59';
        $ventas = DetalleVenta::select(
            'ventas.num_comprobante as Factura',
            'ventas.id',
            'ventas.fecha_hora',
            'personas.id as id_cliente',
            'personas.nombre as nombre_cliente',
            'users.usuario',
            'tipo_ventas.nombre_tipo_ventas as Tipo_venta',
            'roles.nombre as nombre_rol',
            'sucursales.nombre as Nombre_sucursal',
            'articulos.nombre',
            'detalle_ventas.cantidad',
            'detalle_ventas.precio'
        )
        ->join('ventas', 'detalle_ventas.idventa', '=', 'ventas.id')
        ->join('personas', 'ventas.idcliente', '=', 'personas.id')
        ->join('users', 'ventas.idusuario', '=', 'users.id')
        ->join('tipo_ventas', 'ventas.idtipo_venta', '=', 'tipo_ventas.id')
        ->join('roles', 'users.idrol', '=', 'roles.id')
        ->join('sucursales', 'users.idsucursal', '=', 'sucursales.id')
        ->join('articulos', 'detalle_ventas.idarticulo', '=', 'articulos.id')
        ->orderBy('personas.nombre')
        ->orderBy('ventas.fecha_hora')
        ->whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
        ->get();
    
    $totalVentasPorCliente = [];
    
    foreach ($ventas as $venta) {
        $idCliente = $venta->id_cliente;
        $cantidadVenta = $venta->cantidad;
        $precioVenta = $venta->precio;
    
        if (!isset($totalVentasPorCliente[$idCliente])) {
            $totalVentasPorCliente[$idCliente] = [
                'total_cantidad' => 0,
                'total_precio' => 0,
                'index' => null,];
        }
    
        $totalVentasPorCliente[$idCliente]['total_cantidad'] += $cantidadVenta;
        $totalVentasPorCliente[$idCliente]['total_precio'] += $precioVenta;
        $totalVentasPorCliente[$idCliente]['index'] = $venta->id; 
    }
    foreach ($ventas as $venta) {
        $idCliente = $venta->id_cliente;

        if (isset($totalVentasPorCliente[$idCliente]) && $venta->id == $totalVentasPorCliente[$idCliente]['index']) {
            $venta->total_cantidad_cliente = $totalVentasPorCliente[$idCliente]['total_cantidad'];
            $venta->total_precio_cliente = $totalVentasPorCliente[$idCliente]['total_precio'];
        }
    }

    return [
        'ventas' => $ventas,
    ];
    }

}