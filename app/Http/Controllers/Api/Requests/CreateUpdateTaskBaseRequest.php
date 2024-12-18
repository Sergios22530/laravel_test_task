<?php

namespace App\Http\Controllers\Api\Requests;


use App\Enums\PriorityEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreateUpdateTaskBaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', new Enum(StatusEnum::class)],
            'priority' => ['required', 'string', new Enum(PriorityEnum::class)],
            'completed_at' => ['nullable','date', 'date_format:Y-m-d'],
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')],

        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'priority' => 'Priority',
            'completed_at' => 'Completed date',
        ];
    }
}
