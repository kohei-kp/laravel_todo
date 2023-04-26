<?php

namespace App\Http\Requests\Todo;

use App\Models\Todo;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
        return new Todo($this->validated());
    }
}
