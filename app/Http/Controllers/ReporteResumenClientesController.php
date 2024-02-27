<?php

namespace App\Http\Controllers;

use App\CreditoVenta;
use App\User;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteResumenClientesController extends Controller
{ 
    
    public function clientesPorVendedor(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $vendedorId = $request->vendedorId;
        $sucursalId = $request->sucursalId;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        $clientes = Venta::join('personas', 'ventas.idcliente','=','personas.id')
            ->join('cajas', 'ventas.idcaja','=','cajas.id')
            ->join('sucursales', 'cajas.idsucursal','=','sucursales.id')
            ->join('users', 'ventas.idusuario','=','users.iduse');

        $usuarios = User::join('personas', 'users.id', '=', 'personas.id')
            ->where('users.iduse', '=', $vendedorId )
            ->select('personas.nombre as nombre')
            ->orderBy('personas.nombre', 'asc')
            ->get();

        if ($fechaInicio && $fechaFin) {
            $clientes->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);
        }

        if ($sucursalId != 'todos') {
            $clientes->where('sucursales.id','=',$sucursalId);
        }

        if ($vendedorId != 'todos') {
            $clientes->where('users.id','=',$vendedorId);
        }

        $clientes = $clientes
            ->leftJoin('credito_ventas', function ($join) {
                $join->on('personas.id','=','credito_ventas.idcliente')
                    ->where('credito_ventas.estado', '=', 'Pendiente');
            })
            ->select(
                'personas.id as cliente_id',
                'personas.nombre as cliente_nombre',
                DB::raw('SUM(ventas.total) as total_ventas'),
                'sucursales.nombre as nombre_sucursal',
                DB::raw('COALESCE(MAX(credito_ventas.total), 0) as total_deuda'),
                DB::raw('COALESCE(MAX(credito_ventas.numero_cuotas), 0) as numero_cuotas')
            )
            ->groupBy('personas.id', 'personas.nombre', 'sucursales.nombre')
            ->orderBy('personas.id', 'asc')
            ->get();

        return ['clientes' => $clientes,
        'vendedor' => $usuarios
    ];
    }
}