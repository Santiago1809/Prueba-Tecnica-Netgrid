<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Proyecto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    /**
     * Esta función de PHP crea una nueva tarea con los datos proporcionados y devuelve una respuesta
     * JSON con un mensaje de éxito o un mensaje de error si ocurre una excepción.
     * 
     * @param Request request El parámetro  es una instancia de la clase Request, que
     * representa una solicitud HTTP. Contiene todos los datos y la información sobre la solicitud
     * entrante, como el método de solicitud, los encabezados, los parámetros de consulta, los datos
     * del formulario y más.
     * 
     * @return JsonResponse una respuesta Json.
     */
    public function create(Request $request): JsonResponse
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
            ]);
            Tarea::create([
                'idProyecto' => $request->idProyecto,
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'estado' => $request->estado
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
    public function get(int $idProyecto): JsonResponse
    {
        try {
            $proyecto = Proyecto::with('tareas')->findOrFail($idProyecto);

            return response()->json([
                'tareas' => $proyecto->tareas,
                'proyecto' => $proyecto->id
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * La función actualiza una tarea con los datos proporcionados y devuelve una respuesta JSON con un
     * mensaje de éxito o un mensaje de error.
     * @param Request request El parámetro  es una instancia de la clase Request, que
     * representa una solicitud HTTP. Contiene información sobre la solicitud, como el método de
     * solicitud, los encabezados y los datos de entrada.
     * @param int id El parámetro `id` es un número entero que representa el ID de la tarea que debe
     * actualizarse. Se utiliza para encontrar la tarea específica en la base de datos que necesita ser
     * actualizada.
     * @return JsonResponse una respuesta Json.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate([
                'titulo' =>'sometimes|string',
                'descripcion' =>'sometimes|string',
                'estado' =>'sometimes|string',
            ]);
            $tarea = Tarea::findOrFail($id);
    
            $data = [
                'titulo' => $request->titulo?? $tarea->titulo,
                'descripcion' => $request->descripcion?? $tarea->descripcion,
                'estado' => $request->estado?? $tarea->estado,
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
     * La función elimina una tarea con el ID proporcionado y devuelve una respuesta JSON que indica si
     * la eliminación se realizó correctamente o no.
     * @param int id El parámetro "id" es un número entero que representa el identificador único de la
     * tarea que debe eliminarse.
     * @return JsonResponse una respuesta Json.
     */
    public function delete(int $id):JsonResponse
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
    public function getOne(int $id): JsonResponse
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
}
