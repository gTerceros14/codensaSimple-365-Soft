<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\PromocionArticulo;
use Illuminate\Http\Request;
use App\Promocion;

class OfertaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $query = Promocion::query();

        if ($buscar !== '') {
            $query->where($criterio, 'like', '%' . $buscar . '%');
        }

        $ofertas = $query->where('tipo_promocion', 1)
            ->withCount('promocionArticulos as cantidad_articulos')
            ->orderBy('id', 'desc')
            ->paginate(3);

        return response()->json([
            'pagination' => [
                'total' => $ofertas->total(),
                'current_page' => $ofertas->currentPage(),
                'per_page' => $ofertas->perPage(),
                'last_page' => $ofertas->lastPage(),
                'from' => $ofertas->firstItem(),
                'to' => $ofertas->lastItem(),
            ],
            'ofertas' => $ofertas
        ]);
    }

    public function indexKits(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        $query = Promocion::query();

        if ($buscar !== '') {
            $query->where($criterio, 'like', '%' . $buscar . '%');
        }

        $ofertas = $query->where('tipo_promocion', 0)
            ->withCount('promocionArticulos as cantidad_articulos')
            ->orderBy('id', 'desc')
            ->paginate(3);

        return response()->json([
            'pagination' => [
                'total' => $ofertas->total(),
                'current_page' => $ofertas->currentPage(),
                'per_page' => $ofertas->perPage(),
                'last_page' => $ofertas->lastPage(),
                'from' => $ofertas->firstItem(),
                'to' => $ofertas->lastItem(),
            ],
            'ofertas' => $ofertas
        ]);
    }


    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        try {
            DB::beginTransaction();

            $request->validate([
                'codigo' => 'required',
                'precio' => 'required',
                'porcentaje' => 'required',
                'fecha_final' => 'required',
                'estado' => 'required',
                'tipo_promocion' => 'required',
                'nombre' => 'required',
                'articulos' => 'required|array|min:1',
            ]);
            $oferta = new Promocion([
                'codigo' => $request->codigo,
                'precio' => $request->precio,
                'porcentaje' => $request->porcentaje,
                'fecha_final' => $request->fecha_final,
                'estado' => $request->estado,
                'tipo_promocion' => $request->tipo_promocion,
                'nombre' => $request->nombre,
            ]);

            $oferta->save();

            foreach ($request->articulos as $articulo) {
                $cantidad = isset($articulo['cantidad']) ? $articulo['cantidad'] : 0;
                PromocionArticulo::create([
                    'idpromociones' => $oferta->id,
                    'idarticulos' => $articulo['id'],
                    'cantidad' => $cantidad,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Oferta y artículos asociados creados correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['errors' => $e->getMessage()], 422);
        }
    }
    public function obtenerDatosPromocion(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $idPromocion = $request->idPromocion;

        $articulosPorPromocion = PromocionArticulo::with('articulo')->where('idpromociones', $idPromocion)->get();

        return response()->json(['articulosPorPromocion' => $articulosPorPromocion]);
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        try {
            DB::beginTransaction();

            $request->validate([
                'codigo' => 'required',
                'precio' => 'required',
                'porcentaje' => 'required',
                'fecha_final' => 'required',
                'estado' => 'required',
                'tipo_promocion' => 'required',
                'nombre' => 'required',
                'articulos' => 'required|array|min:1',
            ]);
            $oferta = Promocion::findOrFail($request->id);
            $oferta->update([
                'codigo' => $request->codigo,
                'precio' => $request->precio,
                'porcentaje' => $request->porcentaje,
                'fecha_final' => $request->fecha_final,
                'estado' => $request->estado,
                'tipo_promocion' => $request->tipo_promocion,
                'nombre' => $request->nombre,
            ]);

            PromocionArticulo::where('idpromociones', $oferta->id)->delete();

            foreach ($request->articulos as $articulo) {
                $cantidad = isset($articulo['cantidad']) ? $articulo['cantidad'] : 0;

                PromocionArticulo::create([
                    'idpromociones' => $oferta->id,
                    'idarticulos' => $articulo['id'],
                    'cantidad' => $cantidad,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Oferta y artículos asociados actualizados correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['errors' => $e->getMessage()], 422);



        }
    }
    public function modificarEstado(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $oferta = Promocion::findOrFail($request->id);
        $oferta->estado = $request->estado;

        $oferta->save();
    }

}
