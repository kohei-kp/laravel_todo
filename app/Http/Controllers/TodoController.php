<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\StoreRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @return TodoResource
     */
    public function store(StoreRequest $request)
    {
        // バリデーション + 値の構築
        $todo = $request->makeTodo();

        // ドメインロジック
        $todo->user()->associate($request->user());
        $todo->save();

        // レスポンスをResourceでラップ
        return new TodoResource($todo);
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
