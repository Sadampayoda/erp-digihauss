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

            'item_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('items', 'item_code')->ignore($itemId),
            ],

            'name'   => 'required|string|max:255',
            'status' => 'nullable|boolean',

            'brand'  => 'required|exists:brands,id',
            'series' => 'required|exists:series,id',
            'model'  => 'required|string|max:100',

            'storage_gb' => 'required|integer|min:1',
            'ram_gb'     => 'required|integer|min:1',
            'color'      => 'required|string|max:100',

            'network' => 'nullable|string|max:100',
            'region'  => 'nullable|string|max:100',
            'variant' => 'nullable|string|max:100',

            'condition'      => 'required|in:1,2,3',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0|gt:purchase_price',
            'image' => $this->isMethod('post')
                ? 'required|image|mimes:jpg,jpeg,png|max:100120'
                : 'nullable|image|mimes:jpg,jpeg,png|max:100120',

            'images'   => 'nullable|array|max:3',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:100120',
        ];
    }

    public function messages(): array
    {
        return [
            'item_code.required' => 'Item Code wajib diisi',
            'item_code.unique'   => 'Item Code sudah digunakan',

            'brand.required'  => 'Brand wajib dipilih',
            'brand.exists'    => 'Brand tidak valid',

            'series.required' => 'Series wajib dipilih',
            'series.exists'   => 'Series tidak valid',

            'condition.required' => 'Condition wajib dipilih',

            'image.required' => 'Foto utama wajib diupload',
            'image.image'    => 'File harus berupa gambar',
            'sale_price.gt' => 'Harga jual harus lebih besar dari harga beli.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ? 1 : 0,
        ]);
    }
}
