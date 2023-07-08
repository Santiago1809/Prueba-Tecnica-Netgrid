<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $idUsuario)
    {
        try {
            $proyectos = Proyecto::where('idUsuario', $idUsuario)->get();

            $proyectosConTareas = $proyectos->map(function ($proyecto) {
                $proyecto->cantidadTareas = $proyecto->tareas->count();
                return $proyecto;
            });

            return response()->json($proyectosConTareas, 200);
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
            $request->validate([
                'idUsuario' => 'required|integer|exists:users,id',
                'titulo' => 'required|string',
                'descripcion' => 'required|string',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);

            Proyecto::create([
                'idUsuario' => $request->idUsuario,
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
            ]);
            return response()->json([
                'message' => 'Proyecto creado con éxito'
            ], 201);
        } catch (\Throwable $th) {
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
            $proyecto = Proyecto::with('tareas')->findOrFail($id);
            return response()->json($proyecto, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int  $id)
    {
        try {
            $request->validate([
                'titulo' => 'sometimes|string',
                'descripcion' => 'sometimes|string',
                'fecha_inicio' => 'sometimes|date',
                'fecha_fin' => 'sometimes|date',
            ]);
            $proyecto = Proyecto::findOrFail($id);

            $data = [
                'titulo' => $request->filled('titulo') ? $request->titulo : $proyecto->titulo,
                'descripcion' => $request->filled('descripcion') ? $request->descripcion : $proyecto->descripcion,
                'fecha_inicio' => $request->filled('fecha_inicio') ? $request->fecha_inicio : $proyecto->fecha_inicio,
                'fecha_fin' => $request->filled('fecha_fin') ? $request->fecha_fin : $proyecto->fecha_fin,
            ];

            $proyecto->update($data);
            return response()->json([
                'mensaje' => 'Proyecto actualizado con éxito', 'proyecto' => $proyecto
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
            $proyecto = Proyecto::findOrFail($id);
            $proyecto->delete();
            return response()->json([
                'message' => 'Proyecto eliminado con éxito'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
