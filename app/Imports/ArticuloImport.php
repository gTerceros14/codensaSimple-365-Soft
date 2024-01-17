<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Articulo;
use App\Categoria;
use App\Grupo;
use App\Proveedor;
use App\Medida;
use App\Marca;
use App\Industria;
use Illuminate\Support\Facades\Log;
use Exception; // Importa la clase Exception

class ArticuloImport implements ToCollection
{
    private $categoriaMapping;
    private $grupoMapping;
    private $proveedorMapping;
    private $medidaMapping;
    private $marcaMapping;
    private $industriaMapping;

    private $errors = [];

    public function __construct()
    {
        $this->categoriaMapping = $this->createCategoriaMapping();
        $this->grupoMapping = $this->createGrupoMapping();
        $this->proveedorMapping = $this->createProveedorMapping();
        $this->medidaMapping = $this->createMedidaMapping();
        $this->marcaMapping = $this->createMarcaMapping();
        $this->industriaMapping = $this->createIndustriaMapping();
    }

    private function createCategoriaMapping()
    {
        return Categoria::pluck('nombre', 'id')->toArray();
    }

    private function createGrupoMapping()
    {
        return Grupo::pluck('nombre_grupo', 'id')->toArray();
    }

    private function createProveedorMapping()
    {
        return Proveedor::pluck('contacto', 'id')->toArray();
    }

    private function createMedidaMapping()
    {
        return Medida::pluck('descripcion_medida', 'id')->toArray();
    }

    private function createMarcaMapping()
    {
        return Marca::pluck('nombre', 'id')->toArray();
    }

    private function createIndustriaMapping()
    {
        return Industria::pluck('nombre', 'id')->toArray();
    }

    public function collection(Collection $rows)
    {
        $rowNumber = 1;
        $importacionExitosa = true; // Bandera para seguir si la importación es exitosa

        try {
            \DB::beginTransaction();

            foreach ($rows as $row) {
                Log::info('Nombre de categoría en CSV: ' . $row[0]);
                Log::info('Nombre de grupo en CSV: ' . $row[1]);
                Log::info('Nombre de proveedor en CSV: ' . $row[2]);
                Log::info('Descripción de medida en CSV: ' . $row[3]);
                Log::info('Nombre de marca en CSV: ' . $row[20]);
                Log::info('Nombre de industria en CSV: ' . $row[21]);

                $idCategoria = $this->getCategoriaId($row[0]);
                Log::info('ID de categoría obtenido: ' . $idCategoria);

                $idGrupo = $this->getGrupoId($row[1]);
                Log::info('ID de grupo obtenido: ' . $idGrupo);

                $idProveedor = $this->getProveedorId($row[2]);
                Log::info('ID de proveedor obtenido: ' . $idProveedor);

                $idMedida = $this->getMedidaId($row[3]);
                Log::info('ID de medida obtenido: ' . $idMedida);

                $idMarca = $this->getMarcaId($row[20]);
                Log::info('ID de marca obtenido: ' . $idMarca);

                $idIndustria = $this->getIndustriaId($row[21]);
                Log::info('ID de industria obtenido: ' . $idIndustria);

                try {
                    Articulo::create([
                        'idcategoria' => $idCategoria,
                        'idgrupo' => $idGrupo,
                        'idproveedor' => $idProveedor,
                        'idmedida' => $idMedida,
                        'codigo' => $row[4],
                        'nombre' => $row[5],
                        'nombre_generico' => $row[6],
                        'unidad_envase' => $row[7],
                        'precio_list_unid' => $row[8],
                        'precio_costo_unid' => $row[9],
                        'precio_costo_paq' => $row[10],
                        'precio_venta' => $row[11],
                        'precio_uno' => $row[12],
                        'precio_dos' => $row[13],
                        'precio_tres' => $row[14],
                        'precio_cuatro' => $row[15],
                        'stock' => $row[16],
                        'descripcion' => $row[17],
                        'condicion' => $row[18],
                        'costo_compra' => $row[19],
                        'fotografia' => null,
                        'idmarca' => $idMarca,
                        'idindustria' => $idIndustria,
                    ]);
                } catch (Exception $e) {
                    if (!$idCategoria) {
                        $this->errors[] = "Error fila $rowNumber: No existe 'Linea $row[0]' ";
                    } else if (!$idGrupo) {
                        $this->errors[] = "Error fila $rowNumber: No existe 'Grupo $row[1]'";
                    } else if (!$idProveedor) {
                        $this->errors[] = "Error fila $rowNumber: El proveedor '$row[2]' no está registrado";
                    } else if (!$idMedida) {
                        $this->errors[] = "Error fila $rowNumber: La medida '$row[3]' no está registrado en la base de datos";
                    } else if (!$idMarca) {
                        $this->errors[] = "Error fila $rowNumber: No existe 'Marca $row[21]'";
                    } else if (!$idIndustria) {
                        $this->errors[] = "Error fila $rowNumber: No existe 'Industria $row[22]'";
                    } else if (strpos($e->getMessage(), "Integrity constraint violation: 1062") !== false) {
                        $this->errors[] = "Error fila $rowNumber: El producto '$row[5]' ya existe";
                    } else {
                        $this->errors[] = "Error al procesar fila: " . $e->getMessage();
                    }

                    $importacionExitosa = false; // Si hay un error, la importación no es exitosa
                }

                $rowNumber++;
            }

            if ($importacionExitosa) {
                \DB::commit(); // Confirmar la transacción si no hay errores
            } else {
                \DB::rollBack(); // Revertir la transacción en caso de error
            }
        } catch (Exception $e) {
            \DB::rollBack(); // Revertir la transacción en caso de error
            $importacionExitosa = false;
            $this->errors[] = "Error al procesar fila: " . $e->getMessage();
        }

        return $this->getErrorsResponse($importacionExitosa);
    }

    public function getErrors()
    {
        return $this->errors ?? [];
    }

    private function getErrorsResponse($importacionExitosa)
    {
        if (!$importacionExitosa) {
            return response()->json(['errors' => $this->errors], 422);
        } else {
            return response()->json(['mensaje' => 'Importación exitosa'], 200);
        }
    }

    private function getCategoriaId($nombreCategoria)
    {
        $idCategoria = array_search($nombreCategoria, $this->categoriaMapping);

        return $idCategoria !== false ? $idCategoria : null;
    }

    private function getGrupoId($nombreGrupo)
    {
        $idGrupo = array_search($nombreGrupo, $this->grupoMapping);

        return $idGrupo !== false ? $idGrupo : null;
    }

    private function getProveedorId($nombreProveedor)
    {
        $idProveedor = array_search($nombreProveedor, $this->proveedorMapping);

        return $idProveedor !== false ? $idProveedor : null;
    }

    private function getMedidaId($descripcionMedida)
    {
        $idMedida = array_search($descripcionMedida, $this->medidaMapping);

        return $idMedida !== false ? $idMedida : null;
    }

    private function getMarcaId($nombreMarca)
    {
        $idMarca = array_search($nombreMarca, $this->marcaMapping);

        return $idMarca !== false ? $idMarca : null;
    }

    private function getIndustriaId($nombreIndustria)
    {
        $idIndustria = array_search($nombreIndustria, $this->industriaMapping);

        return $idIndustria !== false ? $idIndustria : null;
    }
}
