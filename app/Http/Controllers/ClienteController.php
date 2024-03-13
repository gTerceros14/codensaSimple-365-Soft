<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;
use App\Exports\ClientExport;
use App\User;
use App\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ClienteController extends Controller
{
    // public function index(Request $request)
    // {
    //     if (!$request->ajax()) return redirect('/');

    //     $buscar = $request->buscar;
    //     $criterio = $request->criterio;
        
    //     if ($buscar==''){
    //         $personas = Persona::orderBy('id', 'desc')->paginate(3);
    //     }
    //     else{
    //         $personas = Persona::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(3);
    //     }
        

    //     return [
    //         'pagination' => [
    //             'total'        => $personas->total(),
    //             'current_page' => $personas->currentPage(),
    //             'per_page'     => $personas->perPage(),
    //             'last_page'    => $personas->lastPage(),
    //             'from'         => $personas->firstItem(),
    //             'to'           => $personas->lastItem(),
    //         ],
    //         'personas' => $personas
    //     ];
    // }
    // public function index(Request $request)
    // {
    //     Log::info('Data', [
    //         'usuarioid' => $request->usuarioid,
    //         'buscar' => $request->buscar,
    //         'criterio' => $request->criterio,
    //     ]);

    //     $buscar = $request->buscar;
    //     $criterio = $request->criterio;
    //     $usuarioid = $request->usuarioid;

    //         $usuarios = User::join('personas', 'users.id', '=', 'personas.id')
    //             ->select(
    //                 'personas.id',
    //                 'personas.nombre',

    //                 'personas.tipo_documento',
    //                 'personas.num_documento',
    //                 'personas.direccion',
    //                 'personas.telefono',
    //                 'personas.email',

    //                 'personas.usuario',                   
    //             );
    //             if (!empty($usuarioid)) {
    //                 $usuarios = $usuarios->where('personas.usuario', '=', $usuarioid);
    //             }

    //             if (!empty($buscar)) {
    //                 $usuarios = $usuarios->where(function ($query) use ($criterio, $buscar) {
    //                     $query->where('personas.' . $criterio, 'like', '%' . $buscar . '%');
    //                 });
    //             }
    //               // Ordenamos y paginamos los resultados
    //             $usuarios = $usuarios->orderBy('users.id', 'desc')->paginate(4);

    //             //$usuarios = $usuarios->orderBy('users.id', 'desc')->paginate(4);
    //     return [
    //         'pagination' => [
    //             'total' => $usuarios->total(),
    //             'current_page' => $usuarios->currentPage(),
    //             'per_page' => $usuarios->perPage(),
    //             'last_page' => $usuarios->lastPage(),
    //             'from' => $usuarios->firstItem(),
    //             'to' => $usuarios->lastItem(),
    //         ],
    //         'usuarios' => $usuarios
    //     ];
    // }
    public function index(Request $request)
    {
        Log::info('Data', [
            'buscar' => $request->buscar,
            'criterio' => $request->criterio,
        ]);

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $usuarioid = $request->usuarioid;

        // Consulta para obtener personas que no son usuarios
        $usuarios = Persona::whereNotIn('id', function($query) {
            $query->select('id')->from('users');
        });

        if (!empty($usuarioid)) {
            $usuarios = $usuarios->where('personas.usuario', '=', $usuarioid);
        }

        // Aplicar filtros de búsqueda si están presentes
        if (!empty($buscar)) {
            $usuarios->where(function ($query) use ($criterio, $buscar) {
                $query->where($criterio, 'like', '%' . $buscar . '%');
            });
        }

        // Ordenar y paginar los resultados
        $usuarios = $usuarios->orderBy('id', 'desc')->paginate(4);

        return [
            'pagination' => [
                'total' => $usuarios->total(),
                'current_page' => $usuarios->currentPage(),
                'per_page' => $usuarios->perPage(),
                'last_page' => $usuarios->lastPage(),
                'from' => $usuarios->firstItem(),
                'to' => $usuarios->lastItem(),
            ],
            'usuarios' => $usuarios
        ];
    }

    public function selectCliente(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        $clientes = Persona::where('nombre', 'like', '%'. $filtro . '%')
        ->orWhere('num_documento', 'like', '%'. $filtro . '%')
        ->select('id','nombre', 'tipo_documento', 'num_documento','email','telefono')
        ->orderBy('nombre', 'asc')->get();
 
        return ['clientes' => $clientes];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = new Persona();
        $persona->nombre = $request->nombre;
        $persona->usuario = Auth::user()->iduse;
        //$persona->usuario = Auth::user()->id;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        Log::info('DAtOS PERSONA:', [
            'DATOS' => $persona,
        ]);
        $persona->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $persona = Persona::findOrFail($request->id);
        $persona->nombre = $request->nombre;
        $persona->usuario = $request->usuariodos_id;
        $persona->tipo_documento = $request->tipo_documento;
        $persona->num_documento = $request->num_documento;
        $persona->direccion = $request->direccion;
        $persona->telefono = $request->telefono;
        $persona->email = $request->email;
        $persona->save();
        Log::info('DAtOS ACTU8ALIZAR!!:', [
            'DATOS' => $persona,
        ]);
    }

    public function listarReporteClienteExcel()
    {
        return Excel::download(new ClientExport, 'clientes.xlsx');
    }

    //---seleccionar usuario vendedor--
    public function selectUsuarioVendedor(Request $request){
        if (!$request->ajax()) return redirect('/');
 
        $filtro = $request->filtro;
        $clientes = User::join('personas', 'users.id', '=', 'personas.id')
        ->select(
            'personas.id as ID',
            'personas.nombre',
            'users.idrol',
            'users.iduse as ID_use'
        )  ->where('users.idrol', '=', 2)
            ->orWhere('personas.nombre', 'like', '%'. $filtro . '%')
            ->orderBy('personas.nombre', 'asc')
            //->toSql();
            ->get();
 
        return ['clientes' => $clientes];
    }
     //---listado por id lo que se pidio de Personana--
    public function indexUsuario(Request $request)
    {
         if (!$request->ajax())
             return redirect('/');
 
         $idusuario = $request->idusuario;
         $usuario =User::join('personas', 'users.id', '=', 'personas.id')
         //->join('roles', 'users.idrol', '=', 'roles.id')
         ->select(
             'personas.id as ID', 
             'personas.nombre',
             'personas.usuario',
             //'users.iduse as ID_use'
            )
            //->where('personas.usuario', '=', $idusuario)->get();
            ->where('users.iduse', '=', $idusuario)->get();
         return ['usuario' => $usuario];
    }
    //---listado por id lo que se pidio de Personana--
    public function indexUsuarioFiltro(Request $request)
    {
        if (!$request->ajax())
            return redirect('/');

        //$idusuario = $request->idusuario;
        $filtro = $request->filtro;
        $usuariodos =User::join('personas', 'users.id', '=', 'personas.id')
        //->join('roles', 'users.idrol', '=', 'roles.id')
        ->select(
            'personas.nombre',
            'personas.usuario',
            'users.iduse'
            )
            //->where('personas.usuario', '=', $idusuario)->get();
            ->where('users.idrol', '=', 2)
            ->orWhere('personas.nombre', 'like', '%'. $filtro . '%')
            ->orderBy('personas.nombre', 'asc')
            //->toSql();
            ->get();

        return ['usuariodos' => $usuariodos];
    }

    public function getUserInfo()
    {
        $user = Auth::user();
        return response()->json(['user' => $user]);
    }
    // public function selectUsuarioVendedor(Request $request){
    //     if (!$request->ajax()) return redirect('/');
 
    //     $buscar = $request->buscar;
    //     $criterio = $request->criterio;
        
    //     if ($buscar==''){
    //         $personas = User::join('personas', 'users.id', '=', 'personas.id')
    //             ->join('roles', 'users.idrol', '=', 'roles.id')
    //             ->select(
    //                 'personas.id as ID', 'personas.nombre',
    //                 'roles.id',
    //                 )  ->where('roles.id', '=', '2')->paginate(3);
    //         Persona::orderBy('id', 'desc')->paginate(3);
    //     }
    //     else{
    //         $personas = User::join('personas', 'users.id', '=', 'personas.id')
    //         ->join('roles', 'users.idrol', '=', 'roles.id')
    //         ->select(
    //             'personas.id', 'personas.nombre',
    //             'roles.id as ID',
    //             )  ->where('roles.id', '=', '2')->paginate(3);        }
        

    //     return [
    //         'pagination' => [
    //             'total'        => $personas->total(),
    //             'current_page' => $personas->currentPage(),
    //             'per_page'     => $personas->perPage(),
    //             'last_page'    => $personas->lastPage(),
    //             'from'         => $personas->firstItem(),
    //             'to'           => $personas->lastItem(),
    //         ],
    //         'personas' => $personas
    //     ];
    // }

    public function clientesPorVendedor(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $vendedorId = $request->vendedorId;
        $sucursalId = $request->sucursalId;
        $fechaInicio = $request->fechaInicio;
        $fechaFin = $request->fechaFin;

        $ventas = Venta::leftJoin('personas', 'ventas.idcliente', '=', 'personas.id')
                        ->select('ventas.*', 'personas.nombre as nombre_cliente')
                        ->where('idusuario', $vendedorId);

        if ($fechaInicio && $fechaFin) {
            $ventas->whereBetween('fecha_hora', [$fechaInicio, $fechaFin]);
        }

        if ($sucursalId) {
            $ventas->whereExists(function ($query) use ($sucursalId) {
                $query->select(DB::raw(1))
                    ->from('cajas')
                    ->whereColumn('cajas.id', 'ventas.idcaja')
                    ->where('cajas.idsucursal', $sucursalId);
            });
        }

        $ventas = $ventas->paginate(10);

        $clientes = $ventas->groupBy('idcliente')->map(function ($ventasCliente) {
        $cliente = $ventasCliente->first();
        $cuotasPendientes = DB::table('cuotas_credito')
            ->join('credito_ventas', 'cuotas_credito.idcredito', '=', 'credito_ventas.id')
            ->where('credito_ventas.idpersona', $cliente->idcliente)
            ->where('cuotas_credito.estado', 'Pendiente')
            ->get();

        $precioCuota = $cuotasPendientes->sum('precio_cuota');
        $totalCancelado = $cuotasPendientes->sum('total_cancelado');
        $saldo = $precioCuota - $totalCancelado;

        if ($saldo === 0) {
            $totalCancelado = $cliente->total;
        }

        return [
            'fecha_venta' => $cliente->fecha_hora,
            'nombre_cliente' => $cliente->nombre_cliente,
            'id_cliente' => $cliente->idcliente,
            'id_vendedor' => $cliente->idusuario,
            'tipo_comprobante' => $cliente->tipo_comprobante,
            'total' => $cliente->total,
            'impuesto' => $cliente->impuesto,
            'precio_cuota' => $precioCuota,
            'total_cancelado' => $totalCancelado,
            'saldo' => $saldo,
        ];
        });

        //$clientes = $ventas->paginate(10);

        return ['clientes' => $clientes->values()];
    }
}
