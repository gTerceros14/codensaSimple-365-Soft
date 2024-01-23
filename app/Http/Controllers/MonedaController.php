<?php

namespace App\Http\Controllers;

use App\Empresa;
use Illuminate\Http\Request;
use App\Moneda;

class MonedaController extends Controller
{
    //Listar monedas
    public function index(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $perPage = $request->input('per_page', 5);

        if ($buscar == '') {
            $monedas = Moneda::join('empresas', 'monedas.idempresa', '=', 'empresas.id')
                ->select('monedas.id', 'monedas.idempresa', 'empresas.nombre as nombre_empresa', 'monedas.nombre', 'monedas.pais', 'monedas.simbolo', 'monedas.tipo_cambio', 'monedas.activo', 'monedas.updated_at')
                ->orderBy('monedas.id', 'desc')
                ->paginate($perPage); // Mover el paginate(4) aquí

        } else {
            $monedas = Moneda::join('empresas', 'monedas.idempresa', '=', 'empresas.id')
                ->select('monedas.id', 'monedas.idempresa', 'empresas.nombre as nombre_empresa', 'monedas.nombre', 'monedas.pais', 'monedas.simbolo', 'monedas.tipo_cambio', 'monedas.activo', 'monedas.updated_at')
                ->where('monedas.' . $criterio, 'like', '%' . $buscar . '%')
                ->orderBy('monedas.id', 'desc')
                ->paginate($perPage); // Mover el paginate(4) aquí

        }

        return [
            'pagination' => [
                'total' => $monedas->total(),
                'current_page' => $monedas->currentPage(),
                'per_page' => $monedas->perPage(),
                'last_page' => $monedas->lastPage(),
                'from' => $monedas->firstItem(),
                'to' => $monedas->lastItem(),
            ],
            'monedas' => $monedas
        ];

    }

    public function store(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $moneda = new Moneda();
        $moneda->idempresa = Empresa::first()->id;
        $moneda->nombre = $request->nombre;
        $moneda->pais = $request->pais;
        $moneda->simbolo = $request->simbolo;
        $moneda->tipo_cambio = $request->tipo_cambio;

        $moneda->activo = '1';
        $moneda->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $moneda = Moneda::findOrFail($request->id);
        $moneda->idempresa = $request->idempresa;
        $moneda->nombre = $request->nombre;
        $moneda->pais = $request->pais;
        $moneda->simbolo = $request->simbolo;
        $moneda->tipo_cambio = $request->tipo_cambio;
        $moneda->activo = '1';
        $moneda->save();
    }
    //-----------hasta aqui
    //---desactivar registro

    public function desactivar(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $moneda = Moneda::findOrFail($request->id);
        $moneda->activo = '0';
        $moneda->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');
        $moneda = Moneda::findOrFail($request->id);
        $moneda->activo = '1';
        $moneda->save();
    }
    public function selectMoneda(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $monedas = Moneda::join('empresas', 'monedas.idempresa', '=', 'empresas.id')
            ->where('monedas.activo', 1) // Filtrar por la columna 'activo'
            ->select('monedas.id', 'monedas.nombre', 'monedas.simbolo')
            ->orderBy('monedas.nombre', 'asc')
            ->get();

        return ['monedas' => $monedas];
    }
    public function getMonedaById(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $id = $request->id;

        $moneda = Moneda::join('empresas', 'monedas.idempresa', '=', 'empresas.id')
            ->select('monedas.id', 'monedas.idempresa', 'empresas.nombre as nombre_empresa', 'monedas.nombre', 'monedas.pais', 'monedas.simbolo', 'monedas.tipo_cambio', 'monedas.activo', 'monedas.updated_at')
            ->where('monedas.id', '=', $id)
            ->first();

        return ['moneda' => $moneda];
    }


}
