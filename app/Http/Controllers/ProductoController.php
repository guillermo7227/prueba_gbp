<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;

class ProductoController extends Controller
{
    public function todos(Request $request)
    {
        $filtro = $request->filtro;

        if ($filtro) {
            $productos = Producto::with('bodega')
                                   ->where('nombre', 'like', "%{$filtro}%")
                                   ->paginate(10);
        } else {
            $productos = Producto::with('bodega')->paginate(10);
        }

        return response()->json($productos);
    }


    public function crear(Request $request)
    {
        $input = $request->input();

        $registro = Producto::create($input);

        return response()->json([
            'mensaje' => 'Registro creado exitosamente',
            'registro' => $registro,
        ]);
    }


    public function cambiarEstado(Request $request)
    {
        $id = $request->idProducto;

        $registro = Producto::find($id);

        $registro->estado = $registro->estado == 0 ? 1 : 0;

        $registro->save();

        return response()->json([
            'mensaje' => 'Registro actualizado exitosamente',
            'registro' => $registro,
        ]);
    }

}
