<?php

namespace App\Http\Controllers;

use App\Moneda;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesVentas extends Controller
{
    public function ResumenVentasPorDocumento(Request $request){
        $estado_venta = $request->estado;
        $cliente = $request->idcliente;
        $ejecutivoCuentas = $request->ejecutivoCuentas;
        $idusuario = $request->idusuario;
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->join('tipo_ventas','ventas.idtipo_venta','=','tipo_ventas.id')
        ->join('roles','users.idrol','=','roles.id')
        ->join('sucursales','users.idsucursal','=','sucursales.id')
        ->join('monedas', 'monedas.id', '=', DB::raw('2'))
        ->select('ventas.num_comprobante as Factura',
                'sucursales.nombre as Nombre_sucursal',
                'ventas.fecha_hora as Fecha',
                'monedas.tipo_cambio as Tipo_Cambio',
                'tipo_ventas.nombre_tipo_ventas as Tipo_venta',
                'roles.nombre AS nombre_rol',
                'users.usuario',
                'personas.nombre',
                'ventas.total AS importe_BS',
                DB::raw('ROUND(ventas.total / monedas.tipo_cambio,2) AS importe_usd'))
        ->get();
        $total_importeBs =0;
        $total_importeUSD = 0;
        foreach ($ventas as &$venta){
            $total_importeBs += $venta->importe_BS;
            $total_importeUSD += $venta->importe_usd;
        }
        return['ventas' => $ventas,
                'total BS'=>$total_importeBs,
                'total USD'=>$total_importeUSD];

    } 
}
