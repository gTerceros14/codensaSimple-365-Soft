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

        $ofertas = $query->withCount('promocionArticulos as cantidad_articulos')
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
            // Inicia una transacción
            DB::beginTransaction();

            // Valida los datos de la oferta
            $request->validate([
                'codigo' => 'required',
                'precio' => 'required',
                'porcentaje' => 'required',
                'fecha_final' => 'required',
                'estado' => 'required',
                'tipo_promocion' => 'required',
                'nombre' => 'required',
                'articulos' => 'required|array|min:1', // Se espera un array de objetos de artículos
            ]);

            // Crea una nueva oferta
            $oferta = new Promocion([
                'codigo' => $request->codigo,
                'precio' => $request->precio,
                'porcentaje' => $request->porcentaje,
                'fecha_final' => $request->fecha_final,
                'estado' => $request->estado,
                'tipo_promocion' => $request->tipo_promocion,
                'nombre' => $request->nombre,
            ]);

            // Guarda la oferta en la base de datos
            $oferta->save();

            // Agrega los artículos asociados a la oferta
            foreach ($request->articulos as $articulo) {
                PromocionArticulo::create([
                    'idpromociones' => $oferta->id,
                    'idarticulos' => $articulo['id'],
                    'cantidad' => 0,
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

        // Obtén los datos de la tabla promocion_articulos y sus relaciones con la tabla articulos
        $articulosPorPromocion = PromocionArticulo::with('articulo')->where('idpromociones', $idPromocion)->get();

        return response()->json(['articulosPorPromocion' => $articulosPorPromocion]);
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        try {
            // Inicia una transacción
            DB::beginTransaction();

            // Valida los datos de la oferta
            $request->validate([
                'codigo' => 'required',
                'precio' => 'required',
                'porcentaje' => 'required',
                'fecha_final' => 'required',
                'estado' => 'required',
                'tipo_promocion' => 'required',
                'nombre' => 'required',
                'articulos' => 'required|array|min:1', // Se espera un array de objetos de artículos
            ]);

            // Busca la oferta existente por su ID
            $oferta = Promocion::findOrFail($request->id);

            // Actualiza los datos de la oferta principal
            $oferta->update([
                'codigo' => $request->codigo,
                'precio' => $request->precio,
                'porcentaje' => $request->porcentaje,
                'fecha_final' => $request->fecha_final,
                'estado' => $request->estado,
                'tipo_promocion' => $request->tipo_promocion,
                'nombre' => $request->nombre,
            ]);

            // Elimina los artículos asociados existentes
            PromocionArticulo::where('idpromociones', $oferta->id)->delete();

            // Agrega los artículos asociados actualizados
            foreach ($request->articulos as $articulo) {
                PromocionArticulo::create([
                    'idpromociones' => $oferta->id,
                    'idarticulos' => $articulo['id'],
                    'cantidad' => 0,
                ]);
            }

            // Commit de la transacción si todo ha ido bien
            DB::commit();

            return response()->json(['message' => 'Oferta y artículos asociados actualizados correctamente']);
        } catch (\Exception $e) {
            // Rollback de la transacción en caso de error
            DB::rollBack();

            // Captura la excepción y devuelve los errores
            // return response()->json(['errors' => $e->getMessage()], 422);
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
