<?php

namespace App\Http\Controllers;

use App\ArticulosVariable;
use App\VariableTemporal;
use Illuminate\Http\Request;

class VentasInstitucionalesController extends Controller
{
    public function registrarVariable(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         
        $variables = new VariableTemporal();
        $variables->nombre = $request->nombre;
       
        $variables->save();

        return response()->json(['id' => $variables->id]);
    }

    public function registrarArticuloVariable(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $articulo = new ArticulosVariable();
        $articulo->idvariable = $request->idvariable;
        $articulo->idarticulo = $request->idarticulo;
        $articulo->cantidad = $request->cantidad;
        $articulo->precio = $request->precio;
        $articulo->descuento = $request->descuento;
        $articulo->save();

        $idArticuloVariable = $articulo->id;

        $idVariable = $articulo->idvariable;

        return response()->json(['idArticuloVariable' => $idArticuloVariable, 'idVariable' => $idVariable, 'message' => 'Registro exitoso']);
    }

    public function registrarArticuloVariable2(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $articulo = new ArticulosVariable();
        $articulo->idvariable = $request->idvariable;
        $articulo->idarticulo = $request->idarticulo;
        $articulo->cantidad = $request->cantidad;
        $articulo->precio = $request->precio;
        $articulo->descuento = $request->descuento;
        $articulo->save();

        $idArticuloVariable = $articulo->id;

        $idVariable = $articulo->idvariable;

        return response()->json(['idArticuloVariable' => $idArticuloVariable, 'idVariable' => $idVariable, 'message' => 'Registro exitoso']);
    }



        public function obtenerArticulosPorVariableTemporal(Request $request)
        {
            $idvariable = $request->get('idvariable');

            $nombreVariable = VariableTemporal::find($idvariable)->nombre;

            $articulos = ArticulosVariable::join('articulos', 'articulo_variables.idarticulo', '=', 'articulos.id')
            ->select(
                'articulo_variables.*',
                'articulos.nombre as articulo'
            )
            ->where('articulo_variables.idvariable', $idvariable)
            ->get();
            
            return response()->json(['nombreVariable' => $nombreVariable, 'articulos' => $articulos]);
        }

    public function excluirArticulo(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $id = $request->get('id');
        
        $articulo = ArticulosVariable::findOrFail($id);
        $articulo->delete();

        return response()->json(['message' => 'Artículo eliminado exitosamente']);
    }


}
