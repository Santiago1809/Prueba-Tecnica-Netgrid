<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): JsonResponse
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
     * La función actualiza un proyecto con los datos proporcionados o conserva los datos existentes si
     * no se proporcionan datos nuevos.
     * @param Request request El parámetro  es una instancia de la clase Request, que representa
     * una solicitud HTTP. Contiene información sobre la solicitud, como el método de solicitud, los
     * encabezados y los datos de entrada.
     * @param id El parámetro `id` es el identificador del proyecto que necesita ser actualizado. Se
     * utiliza para encontrar el proyecto en la base de datos y actualizar sus detalles.
     * @return JsonResponse una respuesta JSON. Si la actualización es exitosa, devuelve una respuesta
     * JSON con un mensaje de éxito y los datos del proyecto actualizados. Si hay un error, devuelve una
     * respuesta JSON con un mensaje de error.
     */
    public function update(Request $request, $id): JsonResponse
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
     * La función recupera proyectos asociados con un usuario y los devuelve junto con la cantidad de
     * tareas para cada proyecto en formato JSON.
     * @param int idUsuario El parámetro `idUsuario` es un número entero que representa el ID de un
     * usuario.
     * @return JsonResponse una respuesta Json.
     */
    public function get(int $idUsuario): JsonResponse
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
     * La función elimina un proyecto con la ID dada y devuelve una respuesta JSON con un mensaje de
     * éxito o un mensaje de error si ocurre una excepción. 
     * @param int id El parámetro "id" es un número entero que representa el identificador único del
     * proyecto que debe eliminarse.
     * @return JsonResponse una respuesta Json.
     */
    public function delete(int $id): JsonResponse
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
    public function getOne(int $id): JsonResponse
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
}
