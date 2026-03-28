<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateItemRequest extends FormRequest
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
        $itemId = $this->route('item');
        return [

            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('items', 'code')->ignore($itemId),
            ],

            'name'   => 'required|string|max:255',

            'brand'  => 'required|exists:brands,id',
            'model'  => 'required|string|max:100',

            'image' => $this->isMethod('post')
                ? 'required|image|mimes:jpg,jpeg,png|max:100120'
                : 'nullable|image|mimes:jpg,jpeg,png|max:100120',

            'images'   => 'nullable|array|max:3',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:100120',

            'unit_id' => 'nullable|exists:units,id',

            'type' => [
                'required',
                'integer',
                Rule::in([0, 1, 2]),
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'item_code.required' => 'Item Code wajib diisi',
            'item_code.unique'   => 'Item Code sudah digunakan',

            'brand.required'  => 'Brand wajib dipilih',
            'brand.exists'    => 'Brand tidak valid',

            'image.required' => 'Foto utama wajib diupload',
            'image.image'    => 'File harus berupa gambar',
        ];
    }

}
