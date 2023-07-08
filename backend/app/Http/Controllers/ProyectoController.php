<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $idUsuario)
    {
        try {
            $user = User::findOrFail($idUsuario);

            if ($user->rol === 'administrador') {
                $proyectos = Proyecto::where('idUsuario', $idUsuario)->get();
            } else {
                $proyectos = Proyecto::whereHas('tareas', function ($query) use ($user) {
                    $query->where('responsable', $user->id);
                })->get();
            }

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
            $user = User::findOrFail($request->idUsuario);

            if ($user->rol !== 'administrador') {
                return response()->json([
                    'message' => 'No tienes permiso para crear un proyecto'
                ], 403);
            }

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
    public function show(int $id, Request $request)
    {
        try {
            $user = User::findOrFail($request->idUsuario);

            $proyecto = Proyecto::with('tareas')
                ->where('idUsuario', $user->id)
                ->findOrFail($id);

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
                'idUsuario' => 'required|integer|exists:users,id',
                'titulo' => 'sometimes|string',
                'descripcion' => 'sometimes|string',
                'fecha_inicio' => 'sometimes|date',
                'fecha_fin' => 'sometimes|date',
            ]);
            $user = User::findOrFail($request->idUsuario);
            
            $proyecto = Proyecto::findOrFail($id);

            if ($proyecto->idUsuario !== $request->input('idUsuario')) {
                return response()->json([
                    'message' => 'No tienes permiso para actualizar este proyecto'
                ], 403);
            }

            $data = [
                'titulo' => $request->filled('titulo') ? $request->titulo : $proyecto->titulo,
                'descripcion' => $request->filled('descripcion') ? $request->descripcion : $proyecto->descripcion,
                'fecha_inicio' => $request->filled('fecha_inicio') ? $request->fecha_inicio : $proyecto->fecha_inicio,
                'fecha_fin' => $request->filled('fecha_fin') ? $request->fecha_fin : $proyecto->fecha_fin,
            ];

            $proyecto->update($data);

            return response()->json([
                'mensaje' => 'Proyecto actualizado con éxito',
                'proyecto' => $proyecto
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
    public function destroy(Request $request, int $id)
    {
        try {
            $user = User::findOrFail($request->idUsuario);

            $request->validate([
                'idUsuario' => 'required|integer|exists:users,id',
            ]);

            if ($user->rol !== 'administrador') {
                return response()->json([
                    'message' => 'No tienes permiso para eliminar este proyecto'
                ], 403);
            }

            $proyecto = Proyecto::findOrFail($id);

            if ($proyecto->idUsuario !== $request->input('idUsuario')) {
                return response()->json([
                    'message' => 'No tienes permiso para eliminar este proyecto'
                ], 403);
            }

            $proyecto->delete();

            return response()->json([
                'message' => 'Proyecto eliminado con éxito'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
