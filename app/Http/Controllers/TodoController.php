<?php

namespace App\Http\Controllers;

use App\Http\Requests\Todo\StoreRequest;
use App\Http\Requests\Todo\UpdateRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection<TodoResource>
     */
    public function index()
    {
        return TodoResource::collection(Todo::all());
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
     * @return TodoResource
     */
    public function update(UpdateRequest $request, $id)
    {
        // 認可とバリデーション
        $validatedTodo = $request->makeTodo();

        // ドメインロジック
        $todo = Todo::where('id', $id)->first();
        $todo->update($validatedTodo->toArray());

        return new TodoResource($todo);
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
