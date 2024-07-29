<?php

namespace App\Http\Controllers;

use App\DetalleIngreso;
use App\Ingreso;
use App\IngresoCuota;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IngresoCuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect("/");
        }

        $ingresos = Ingreso::join(
            "personas as proveedor",
            "ingresos.idproveedor",
            "=",
            "proveedor.id"
        )
            ->join("users", "ingresos.idusuario", "=", "users.id")
            ->join("personas as usuario", "users.id", "=", "usuario.id")
            ->join("almacens", "ingresos.idalmacen", "=", "almacens.id")
            ->leftJoin("ingresos_cuotas", function ($join) {
                $join
                    ->on("ingresos.id", "=", "ingresos_cuotas.idingreso")
                    ->where("ingresos_cuotas.estado", "=", "Pendiente");
            })
            ->select(
                "ingresos.id",
                "ingresos.tipo_comprobante",
                "ingresos.serie_comprobante",
                "ingresos.num_comprobante",
                "ingresos.total",
                "ingresos.num_cuotas",
                "ingresos.frecuencia_cuotas",
                "ingresos.cuota_inicial",
                "ingresos.tipo_pago_cuota as tipo_pago_cuota_inicial",
                "ingresos.idalmacen",
                "ingresos.descuento_global",
                "almacens.nombre_almacen",
                "proveedor.nombre as proveedor",
                "usuario.nombre as usuario",
                DB::raw(
                    'CASE WHEN SUM(ingresos_cuotas.precio_cuota) IS NULL THEN "Pagado" ELSE "Pendiente" END as estado_pago'
                ),
                DB::raw(
                    "COALESCE(SUM(ingresos_cuotas.precio_cuota), 0) as saldo_restante"
                )
            )
            ->groupBy(
                "ingresos.id",
                "ingresos.tipo_comprobante",
                "ingresos.serie_comprobante",
                "ingresos.num_comprobante",
                "ingresos.total",
                "ingresos.num_cuotas",
                "ingresos.frecuencia_cuotas",
                "ingresos.cuota_inicial",
                "ingresos.tipo_pago_cuota",
                "ingresos.idalmacen",
                "ingresos.descuento_global",
                "almacens.nombre_almacen",
                "proveedor.nombre",
                "usuario.nombre"
            )
            ->orderBy("ingresos.id", "desc")
            ->where("ingresos.tipoCompra", "=", 2)
            ->get();

        $ingresos = $ingresos->map(function ($ingreso) {
            $ingreso->saldo_restante = number_format($ingreso->saldo_restante,2,".", "");
            return $ingreso;
        });

        return response()->json(
            [
                "status" => "success",
                "message" => "Ingresos obtenidos con exito",
                "ingresos" => $ingresos,
            ],
            200
        );
    }

    public function listarComprasCredito(Request $request)
    {
        if (!$request->ajax()) {
            return redirect("/");
        }

        try {
            $comprasCredito = DB::table('ingresos')
                ->join('proveedores', 'ingresos.idproveedor', '=', 'proveedores.id')
                ->join('personas as proveedores_personas', 'proveedores.id', '=', 'proveedores_personas.id')
                ->join('users', 'ingresos.idusuario', '=', 'users.id')
                ->join('personas as usuarios', 'users.id', '=', 'usuarios.id')
                ->leftJoin('ingresos_cuotas', 'ingresos.id', '=', 'ingresos_cuotas.idingreso')
                ->leftJoin('almacens', 'ingresos.idalmacen', '=', 'almacens.id')
                ->select(
                    'ingresos.id',
                    'ingresos.tipo_comprobante',
                    'ingresos.fecha_hora',
                    'ingresos.cuota_inicial',
                    'proveedores_personas.nombre as proveedor',
                    'usuarios.nombre as usuario',
                    'ingresos.total as total_compra',
                    DB::raw('SUM(ingresos_cuotas.total_cancelado) as total_pagado'),
                    DB::raw('COUNT(CASE WHEN ingresos_cuotas.estado = "Pendiente" THEN 1 END) as cuotas_pendientes'),
                    DB::raw('CONCAT(COUNT(CASE WHEN ingresos_cuotas.estado = "Pagado" THEN 1 END), "/", ingresos.num_cuotas) as num_cuotas'),
                    'ingresos.estado as estado_compra',
                    DB::raw('SUM(CASE WHEN ingresos_cuotas.estado = "Pendiente" THEN ingresos_cuotas.precio_cuota ELSE 0 END) as total_restante'),
                    'almacens.nombre_almacen as nombre_almacen'
                )
                ->where('ingresos.tipoCompra', 2)
                ->groupBy(
                    'ingresos.id',
                    'ingresos.tipo_comprobante',
                    'ingresos.fecha_hora',
                    'ingresos.cuota_inicial',
                    'proveedores_personas.nombre',
                    'usuarios.nombre',
                    'ingresos.total',
                    'ingresos.num_cuotas',
                    'ingresos.estado',
                    'almacens.nombre_almacen'
                )
                ->orderBy("ingresos.id", "desc")
                ->get();

            return response()->json([
                "status" => "success",
                "message" => "Ingresos obtenidos con exito",
                "ingresos" => $comprasCredito]);

        } catch (QueryException $e) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => 'Error al ejecutar la consulta: ' . $e->getMessage()
                ],
                500);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => 'Ha ocurrido un error inesperado: ' . $e->getMessage()
                ],
                500);
        }
    }

    public function getCuotasByIngreso(Request $request)
    {
        if (!$request->ajax()) {
            return redirect("/");
        }

        try {
            $id = $request->id;
            $cuotas = IngresoCuota::where("idingreso", $id)
                ->select(
                    "id",
                    "fecha_pago",
                    "precio_cuota",
                    "total_cancelado",
                    "saldo_restante",
                    "fecha_cancelado",
                    "tipo_pago_cuota",
                    "estado"
                )
                ->get();

            return response()->json(
                [
                    "status" => "success",
                    "message" => "Cuotas obtenidas con exito",
                    "cuotas" => $cuotas,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => $e->getMessage(),
                    "line" => $e->getLine(),
                    "file" => $e->getFile(),
                ],
                500
            );
        }
    }

    public function pagarCuota(Request $request)
    {
        if (!$request->ajax()) {
            return redirect("/");
        }

        try {
            DB::beginTransaction();

            if (!isset($request->form) || !is_array($request->form)) {
                throw new \Exception("Los datos del formulario no son validos");
            }

            $cuota = IngresoCuota::findOrFail($request->id);

            if ($cuota->estado === "Pagado") {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Esta cuota ya ha sido pagada",
                    ],
                    400
                );
            }

            if ($request->form["pago_actual"] != $cuota->precio_cuota) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" =>
                            "El monto del pago debe ser igual al precio de la cuota",
                    ],
                    400
                );
            }

            $cuota->total_cancelado = $request->form["pago_actual"];
            $cuota->saldo_restante = 0;
            $cuota->fecha_cancelado = $request->form["fecha_pago"];
            $cuota->tipo_pago_cuota = $request->form["tipo_pago_cuota"]["nombre"];
            $cuota->estado = "Pagado";
            $cuota->save();

            $ingreso = Ingreso::findOrFail($cuota->idingreso);
            $todasCuotasPagadas = $ingreso->cuotas()
                ->where("estado", "!=", "Pagado")
                ->where("estado", "!=", "Cuota Inicial")
                ->count();

            if ($todasCuotasPagadas === 0) {
                $ingreso->estado = "Pagado";
                $ingreso->save();
            }

            DB::commit();

            return response()->json(
                [
                "status" => "success",
                "message" => "Cuota pagada exitosamente",
                ],
                200
            );
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(
                [
                    "status" => "error",
                    "message" =>
                        "Error al procesar el pago: " . $e->getMessage(),
                ],
                500
            );
        }
    }

    public function listarDetalleIngreso(Request $request)
    {
        if (!$request->ajax()) {
            return redirect("/");
        }

        try {
            $id = $request->id;

            $ingreso = Ingreso::findOrFail($id);

            $articulos = $ingreso->detalles()->with('articulo')->get()->map(function ($detalle) {
                return [
                    'id' => $detalle->articulo->id,
                    'nombre' => $detalle->articulo->nombre,
                    'cantidad' => $detalle->cantidad,
                    'precio' => $detalle->precio,
                    'descuento' => $detalle->descuento
                ];
            });

            $data = [
                'ingreso' => $ingreso,
                'articulos' => $articulos
            ];

            return response()->json(
                [
                    "status" => "success",
                    "message" => "Datos recuperados con exito",
                    "datos" => $data,
                ],
                200
            );

        } catch(\Exception $e) {
            return response()->json(
                [
                    "status" => "error",
                    "message" =>
                        "Error al recuperar datos: " . $e->getMessage(),
                ],
                500
            );
        }

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\IngresoCuota  $ingresoCuota
     * @return \Illuminate\Http\Response
     */
    public function show(IngresoCuota $ingresoCuota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\IngresoCuota  $ingresoCuota
     * @return \Illuminate\Http\Response
     */
    public function edit(IngresoCuota $ingresoCuota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\IngresoCuota  $ingresoCuota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IngresoCuota $ingresoCuota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\IngresoCuota  $ingresoCuota
     * @return \Illuminate\Http\Response
     */
    public function destroy(IngresoCuota $ingresoCuota)
    {
        //
    }
}
