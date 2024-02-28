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
}
