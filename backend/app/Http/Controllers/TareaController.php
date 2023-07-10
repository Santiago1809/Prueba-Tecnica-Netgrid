<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $idProyecto)
    {
        try {
            $proyecto = Proyecto::with('tareas')->findOrFail($idProyecto);

            return response()->json([
                'tareas' => $proyecto->tareas,
                'proyecto' => $proyecto->titulo
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            /* El código `->validate([...]);` está validando los datos de la solicitud entrante
            contra un conjunto de reglas. En este caso, las reglas especifican que los campos
            `idProyecto`, `titulo`, `descripcion` y `estado` son obligatorios y deben ser de tipos
            de datos específicos (`integer` y `string`). */
            $request->validate([
                'idProyecto' => 'required|integer|exists:proyectos,id',
                'titulo' => 'required|string',
                'descripcion' => 'required|string',
                'estado' => 'required|string',
                'responsable' => 'required|integer',
            ]);
            $user = User::findOrFail($request->idUsuario);
            if ($user->rol !== 'administrador') {
                return response()->json([
                    'message' => 'No tienes permiso para crear una tarea'
                ], 403);
            }
            Tarea::create([
                'idProyecto' => $request->idProyecto,
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'estado' => $request->estado,
                'responsable' => $request->responsable,
            ]);
            /* El código `return response()->json(['message' => 'Tarea creada correctamente'], 201);`
            está devolviendo una respuesta JSON con un mensaje de éxito y un código de estado de 201
            (Creado). */
            return response()->json([
                'message' => 'Tarea creada correctamente'
            ], 201);
        } catch (\Throwable $th) {
            /* El código `return response()->json(['message' => ->getMessage()], 500);` está
            devolviendo una respuesta JSON con un mensaje de error y un código de estado de 500
            (Error interno del servidor) . */
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $tarea = Tarea::findOrFail($id);
            return response()->json([
                'tarea' => $tarea
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $request->validate([
                'titulo' => 'sometimes|string',
                'descripcion' => 'sometimes|string',
                'estado' => 'sometimes|string',
                'responsable'=>'sometimes|integer',
            ]);
            $tarea = Tarea::findOrFail($id);

            $data = [
                'titulo' => $request->titulo ?? $tarea->titulo,
                'descripcion' => $request->descripcion ?? $tarea->descripcion,
                'estado' => $request->estado !== 'vacio' ? $request->estado : $tarea->estado,
                'responsable' => isset($request->responsable) && $request->responsable !== 0 ? $request->responsable : $tarea->responsable,
            ];            
            
            
            $tarea->update($data);
            return response()->json([
                'message' => 'Tarea actualizada correctamente'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {

            $tarea = Tarea::findOrFail($id);
            $tarea->delete();
            return response()->json([
                'message' => 'Tarea eliminada correctamente'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
