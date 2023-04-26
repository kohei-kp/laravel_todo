<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $todos = Todo::all();

        return response()->json([
            'todos' => $todos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'task_name' => 'required|string|max:255',
            'task_description' => 'nullable|string',
            'is_completed' => 'boolean',
        ]);

        $todo = new Todo();
        $todo->task_name = $validate['task_name'];
        if (isset($validate['task_description'])) {
            $todo->task_description = $validate['task_description'];
        }
        $todo->is_completed = $validate['is_completed'];
        $todo->save();

        return response()->json([
            'todo' => $todo,
            'message' => 'Todo created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $todo = Todo::FindOrFail($id);
        if (! $todo) {
            return response()->json([
                'message' => 'Todo not found',
            ], 404);
        }

        return response()->json([
            'todo' => $todo,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'task_name' => 'required|string|max:255',
            'task_description' => 'nullable|string',
            'is_completed' => 'boolean',
        ]);

        $todo = Todo::where('id', $id)->firstOrFail();
        $todo->task_name = $validate['task_name'];
        $todo->task_description = $validate['task_description'];
        $todo->is_completed = $validate['is_completed'];

        return response()->json([
            'todo' => $todo,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $todo = Todo::where('id', $id)->firstOrFail();
        $todo->delete();

        return response()->json([
            'message' => 'Todo deleted successfully',
        ], 200);
    }
}
