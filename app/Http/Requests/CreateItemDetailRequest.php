<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateItemDetailRequest extends FormRequest
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
        $id = $this->route('id') ?? $this->route('item');

        return [
            'item_id' => 'required|exists:item,id',

            'color' => 'required|string|max:100',
            'internal_storage' => 'required|integer|min:1',
            'network' => 'required|string|max:50',
            'region' => 'nullable|string|max:50',

            'imei' => [
                'required',
                'digits:15',
                Rule::unique('item_details', 'imei')->ignore($id)
            ],

            'serial_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('item_details', 'serial_number')->ignore($id)
            ],

            'type' => 'required|in:new,second',

            'has_box' => 'nullable|boolean',
            'has_cable' => 'nullable|boolean',
            'has_adapter' => 'nullable|boolean',

            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'service' => 'nullable|numeric|min:0',

            'distributor' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'sale_date' => 'nullable|date|after_or_equal:purchase_date',
        ];
    }

    public function messages(): array
    {
        return [
            'item_id.required' => 'Model item wajib dipilih.',
            'imei.digits' => 'IMEI harus 15 digit.',
            'imei.unique' => 'IMEI sudah terdaftar.',
            'serial_number.unique' => 'Serial number sudah digunakan.',
            'type.required' => 'Tipe item wajib dipilih.',
            'sale_date.after_or_equal' => 'Tanggal jual tidak boleh sebelum tanggal beli.'
        ];
    }
}
