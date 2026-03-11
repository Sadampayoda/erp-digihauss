<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateItemConditionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $condition = $this->route('condition');
        $conditionId = is_object($condition) ? $condition->id : $condition;
        return [

            'item_detail_id' => [
                'required',
                'exists:item_details,id',
                Rule::unique('item_conditions', 'item_detail_id')
                    ->ignore($conditionId, 'id')
            ],

            'battery_health' => 'nullable|integer|min:0|max:100',

            'body_condition' => 'nullable|in:excellent,good,fair,bad',
            'lcd_condition' => 'nullable|in:excellent,good,fair,bad',
            'housing_condition' => 'nullable|in:excellent,good,fair,bad',

            'face_id_condition' => 'nullable|in:working,not_working',
            'battery_condition' => 'nullable|in:normal,service,unknown',

            'front_camera_condition' => 'nullable|in:working,not_working',
            'rear_camera_condition' => 'nullable|in:working,not_working',

            'speaker_top_condition' => 'nullable|in:clear,low,broken',
            'speaker_bottom_condition' => 'nullable|in:clear,low,broken',

            'ready' => 'nullable|boolean',

            'notes' => 'nullable|string|max:1000',

        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'ready' => $this->ready ?? 0
        ]);
    }

    public function messages(): array
    {
        return [
            'item_detail_id.unique' => 'Barang ini sudah memiliki data kondisi.',
            'item_detail_id.exists' => 'Barang tidak ditemukan.',
        ];
    }
}
