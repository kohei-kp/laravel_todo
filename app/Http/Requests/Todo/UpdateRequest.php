<?php

namespace App\Http\Requests\Todo;

use App\Models\Todo;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Gate $gate): bool
    {
        // 認可
        $todoId = $this->route()->parameter('id');

        return $gate->authorize('update', [Todo::class, $todoId])->allowed();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'task_name' => 'required|string|max:255',
            'task_description' => 'nullable|string',
            'is_completed' => 'boolean',
        ];
    }

    public function makeTodo(): Todo
    {
        $todo = $this->validated();
        $todo['user_id'] = $this->user()->id;
        $todo['id'] = $this->route()->parameter('id');

        return new Todo($todo);
    }
}
