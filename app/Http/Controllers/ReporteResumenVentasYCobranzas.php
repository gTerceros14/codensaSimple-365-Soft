<?php

namespace App\Http\Controllers;

use App\DetalleVenta;
use App\TipoPago;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteResumenVentasYCobranzas extends Controller
{
  public function ventasYcobranzas(Request $request)
  {

    if (!$request->ajax()) {
      return redirect('/');
    }

    /*$detalles = DetalleVenta::join('ventas', 'ventas.id','=','detalle_ventas.idventa')
        ->join('personas', 'personas.id','=','ventas.idcliente')
        ->join('users', 'users.id','=','ventas.idusuario')
        ->join('sucursales', 'sucursales.id','=','users.idsucursal')
        ->join('tipo_pagos', 'tipo_pagos.id','=','ventas.idtipo_pago')
        ->join('tipo_ventas', 'tipo_ventas.id','=','ventas.idtipo_venta')*/

    $vendedorId = 6;

    $resultados = DB::table('tipo_pagos')
      ->leftJoin('ventas', function ($join) use ($vendedorId) {
        $join->on('tipo_pagos.id', '=', 'ventas.idtipo_pago')
          ->where('ventas.idusuario', '=', $vendedorId);
      })
      ->select(
        DB::raw('IFNULL(SUM(CASE WHEN tipo_pagos.nombre_tipo_pago = "Efectivo" THEN ventas.total ELSE 0 END), 0) AS tipo_pago_Efectivo'),
        DB::raw('IFNULL(SUM(CASE WHEN tipo_pagos.nombre_tipo_pago = "Tarjeta" THEN ventas.total ELSE 0 END), 0) AS tipo_pago_Tarjeta'),
        DB::raw('IFNULL(SUM(CASE WHEN tipo_pagos.nombre_tipo_pago = "Transacion QR" THEN ventas.total ELSE 0 END), 0) AS tipo_pago_TransaccionQR'),
        DB::raw('COALESCE(SUM(ventas.total), 0) AS total_ventas')
      )
      ->first();

    return response()->json($resultados);
  }
}
