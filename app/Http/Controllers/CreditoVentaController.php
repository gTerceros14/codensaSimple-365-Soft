<?php

namespace App\Http\Controllers;

use App\Venta;
use Illuminate\Support\Facades\Log;
use App\CreditoVenta;
use App\CuotasCredito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreditoVentaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $perPage = $request->input('per_page', 3);
        $filtroAvanzado = $request->filtro_avanzado;

        $creditosQuery = CreditoVenta::query()
            ->join('ventas', 'credito_ventas.idventa', '=', 'ventas.id')
            ->join('personas as clientes', 'credito_ventas.idcliente', '=', 'clientes.id')
            ->join('personas as vendedores', 'ventas.idusuario', '=', 'vendedores.id')
            ->select(
                'credito_ventas.id',
                'credito_ventas.numero_cuotas',
                'credito_ventas.tiempo_dias_cuota',
                'credito_ventas.total',
                'credito_ventas.estado',
                'credito_ventas.proximo_pago',

                'ventas.tipo_comprobante',
                'ventas.num_comprobante',
                'ventas.fecha_hora',
                'ventas.total as totalVenta',
                'clientes.nombre as nombre_cliente',
                'vendedores.nombre as nombre_vendedor'
            );


        // Aplicar filtro de búsqueda
        if ($buscar && $criterio) {
            if ($criterio === 'nombre_cliente') {
                $creditosQuery->where('clientes.nombre', 'like', '%' . $buscar . '%');
            } elseif ($criterio === 'nombre_vendedor') {
                $creditosQuery->where('vendedores.nombre', 'like', '%' . $buscar . '%');
            } elseif ($criterio === 'proximos_pago') {
                // Lógica para manejar la búsqueda por pagos cercanos
            }
        }
        Log::info($filtroAvanzado);
        // Aplicar filtro avanzado
        if ($filtroAvanzado) {
            if ($filtroAvanzado === 'pagos_atrasados') {
                // Calcular la fecha actual
                $fechaActual = now();

                // Aplicar el filtro para los pagos que ya vencieron
                $creditosQuery->where('credito_ventas.proximo_pago', '<', $fechaActual)
                    ->where('credito_ventas.estado', '!=', 'Completado');
            } elseif ($filtroAvanzado === 'pagos_hoy') {
                // Calcular la fecha actual
                $fechaActual = now();

                // Aplicar el filtro para los pagos que deben realizarse hoy
                $creditosQuery->whereDate('credito_ventas.proximo_pago', $fechaActual)
                    ->where('credito_ventas.estado', '!=', 'Completado');
            } elseif ($filtroAvanzado === 'pagos_cercanos') {
                $fechaActual = now();

                // Definir el rango de días para considerar como "cercanos"
                $diasCercanos = 7; // Por ejemplo, considerar los pagos de los próximos 7 días como cercanos

                // Calcular la fecha límite para los pagos cercanos
                $fechaLimite = $fechaActual->copy()->addDays($diasCercanos);

                // Aplicar el filtro para los pagos que están dentro del rango de días cercanos
                $creditosQuery->where('credito_ventas.proximo_pago', '<=', $fechaLimite)
                    ->where('credito_ventas.estado', '!=', 'Completado');
            } elseif ($filtroAvanzado === 'pagos_completados') {
                // Aplicar el filtro para los pagos que ya están completados
                $creditosQuery->where('credito_ventas.estado', '=', 'Completado');
            }
        }

        $creditosPaginated = $creditosQuery->orderBy('credito_ventas.id', 'desc')
            ->paginate($perPage);

        return [
            'pagination' => [
                'total' => $creditosPaginated->total(),
                'current_page' => $creditosPaginated->currentPage(),
                'per_page' => $creditosPaginated->perPage(),
                'last_page' => $creditosPaginated->lastPage(),
                'from' => $creditosPaginated->firstItem(),
                'to' => $creditosPaginated->lastItem(),
            ],
            'creditos' => $creditosPaginated
        ];
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'idventa' => 'required|integer|exists:ventas,id',
                'idcliente' => 'required|integer|exists:personas,id',
                'cuotas' => 'required|array|min:1',
                'cuotas.*.fecha_pago' => 'required|date',
                'cuotas.*.precio_cuota' => 'required|numeric|min:0',
                'cuotas.*.saldo_restante' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'estado' => 'required|string|in:activo,inactivo'
            ]);

            $credito = new CreditoVenta();
            $credito->idventa = $request->idventa;
            $credito->idcliente = $request->idcliente;
            $credito->numero_cuotas = count($request->cuotas);
            $credito->tiempo_dias_cuota = $request->tiempo_dias_cuota;
            $credito->total = $request->total;
            $credito->estado = $request->estado;
            $credito->save();
            foreach ($request->cuotas as $cuotaData) {
                $cuota = new CuotasCredito();
                $cuota->idcredito = $credito->id;
                $cuota->fecha_pago = $cuotaData['fecha_pago'];
                $cuota->fecha_cancelado = null;
                $cuota->precio_cuota = $cuotaData['precio_cuota'];
                $cuota->total_cancelado = 0;
                $cuota->saldo_restante = $cuotaData['saldo_restante'];
                $cuota->estado = $cuotaData['estado'];
                $cuota->save();
            }
            DB::commit();

            return $credito;
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Error al procesar la solicitud. Inténtalo de nuevo más tarde.']);
        }
    }

    public function obtenerCuotasCredito(Request $request)
    {
        try {
            $request->validate([
                'id_credito' => 'required|integer|exists:credito_ventas,id',
            ]);

            $idCredito = $request->id_credito;

            $cuotas = CuotasCredito::where('idcredito', $idCredito)
                ->leftJoin('personas', 'cuotas_credito.idcobrador', '=', 'personas.id')
                ->select('cuotas_credito.*', DB::raw('IFNULL(personas.nombre, "Pendiente") as nombre_cobrador'))
                ->get();

            return $cuotas;
        } catch (\Exception $e) {
            throw ValidationException::withMessages(['error' => 'Error al obtener las cuotas de crédito. Inténtalo de nuevo más tarde.']);
        }
    }


    public function registrarPagoCuota(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'id_credito' => 'required|integer|exists:credito_ventas,id',
                'numero_cuota' => 'required|integer',
                'monto_pago' => 'required|numeric|min:0',
            ]);
            $idCredito = $request->id_credito;
            $numeroCuota = $request->numero_cuota;
            $montoPago = $request->monto_pago;
            $valorCuotaAnterior = $request->cuota_anterior;
            $credito = CreditoVenta::findOrFail($idCredito);

            $idVenta = $credito->idventa;
            $credito->total -= $montoPago;
            if ($credito->total <= 0) {
                Venta::where('id', $idVenta)->update(['estado' => 'Registrado']);
                $credito->estado = 'Completado';
            }
            $credito->save();

            $cuota = CuotasCredito::where('idcredito', $idCredito)
                ->where('numero_cuota', $numeroCuota)
                ->firstOrFail();

            $cuota->fecha_cancelado = now();
            $cuota->idcobrador = \Auth::user()->id;

            $cuota->estado = 'Pagado';
            $cuota->saldo_restante = $valorCuotaAnterior - $montoPago;
            $cuota->save();
            DB::commit();

            return $cuota;
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Error al procesar la solicitud. Inténtalo de nuevo más tarde.']);
        }
    }
    public function obtenerCreditoYCuotas(Request $request)
    {
        $creditoVenta = CreditoVenta::where('idventa', $request->idventa)->first();

        if (!$creditoVenta) {
            return response()->json(['error' => 'No se encontró ningún crédito de venta para el idventa proporcionado.'], 404);
        }

        $cuotasCredito = CuotasCredito::where('idcredito', $creditoVenta->id)->get();

        return response()->json([
            'creditoVenta' => $creditoVenta,
            'cuotasCredito' => $cuotasCredito
        ], 200);
    }
}
