<?php

namespace App\Http\Controllers;

use App\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesInventariosController extends Controller
{
    public function inventarioFisicoValorado(Request $request,$tipo){
        
        
        
       
        $fechaVencimiento = $request->fecha_vencimiento;
        if ($tipo === 'item'){
            $resultados = DB::table('inventarios')
                ->join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
                ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                ->join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                ->join('marcas', 'articulos.idmarca', '=', 'marcas.id')
                ->join('industrias', 'articulos.idindustria', '=', 'industrias.id')
                ->select(
                    'articulos.nombre AS nombre_producto',
                    'articulos.unidad_envase',
                    'almacens.nombre_almacen',
                    DB::raw('SUM(inventarios.saldo_stock) AS saldo_stock_total'),
                    DB::raw('(SUM(inventarios.saldo_stock) * articulos.precio_costo_unid) AS costo_total'), 
                    'categorias.nombre AS nombre_categoria',
                    'marcas.nombre AS nombre_marca',
                    'industrias.nombre AS nombre_industria'
                )
                ->where('inventarios.fecha_vencimiento','<=', $fechaVencimiento)
                ->groupBy(
                    'articulos.nombre',
                    'articulos.unidad_envase',
                    'almacens.nombre_almacen',
                    'categorias.nombre',
                    'marcas.nombre',
                    'industrias.nombre',
                    'articulos.precio_costo_unid' 
                )
                ->orderBy('articulos.nombre')
                ->orderBy('almacens.nombre_almacen');

        }
        else if ($tipo === 'lote'){
            $resultados = DB::table('inventarios')
                ->join('almacens', 'inventarios.idalmacen', '=', 'almacens.id')
                ->join('articulos', 'inventarios.idarticulo', '=', 'articulos.id')
                ->join('categorias', 'articulos.idcategoria', '=', 'categorias.id')
                ->join('marcas', 'articulos.idmarca', '=', 'marcas.id')
                ->join('industrias', 'articulos.idindustria', '=', 'industrias.id')
                ->select(
                    'articulos.nombre AS nombre_producto',
                    'articulos.unidad_envase',
                    'articulos.precio_costo_unid',
                    'inventarios.saldo_stock',
                    DB::raw('(inventarios.saldo_stock * articulos.precio_costo_unid) AS costo_total'), 
                    DB::raw('DATE_FORMAT(inventarios.created_at, "%Y-%m-%d") AS fecha_ingreso'),
                    'inventarios.fecha_vencimiento',
                    'almacens.nombre_almacen',
                    'categorias.nombre AS nombre_categoria',
                    'marcas.nombre AS nombre_marca',
                    'industrias.nombre AS nombre_industria'
                )
                ->where('inventarios.fecha_vencimiento','<=',$fechaVencimiento)
                ->orderBy('articulos.nombre');

        }
        if ($request->has('idAlmacen') && $request->idAlmacen !== 'undefined') {
            $idAlmacen = $request->idAlmacen;
            $resultados->where('almacens.id', $idAlmacen);
        }
        if ($request->has('idArticulo') && $request->idArticulo !== 'undefined') {
            $idArticulo = $request->idArticulo;
            $resultados->where('articulos.id' , $idArticulo);
        }
        if ($request->has('idMarca') && $request->idMarca !== 'undefined') {
            $idMarca = $request->idMarca;
            $resultados->where('articulos.idmarca' , $idMarca);   
        }
        if ($request->has('idLinea') && $request->idLinea !== 'undefined') {
            $idLinea = $request->idLinea;
            $resultados->where('articulos.idcategoria' , $idLinea);
            
        }
        if ($request->has('idIndustria') && $request->idIndustria !== 'undefined') {
            $idIndustria = $request->idIndustria;
            $resultados->where('articulos.idindustria' , $idIndustria);
            
        }
        $resultados=$resultados->get();
        return ['inventarios_valorado' => $resultados];

    }
}
