<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateServiceRequest extends FormRequest
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
        return [
            'item_detail_id' => [
                'required',
                'exists:item_details,id'
            ],

            'transaction_date' => [
                'required',
                'date'
            ],

            'payment_method' => [
                'required',
                'exists:payment_methods,id'
            ],

            'service' => [
                'required',
                'numeric',
                'min:0'
            ],

            'description' => [
                'nullable',
                'string',
                'max:255'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'item_detail_id.required' => 'SN / IMEI wajib dipilih.',
            'item_detail_id.exists' => 'SN / IMEI tidak valid.',

            'transaction_date.required' => 'Tanggal service wajib diisi.',
            'transaction_date.date' => 'Format tanggal tidak valid.',

            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.exists' => 'Metode pembayaran tidak valid.',

            'service.required' => 'Biaya service wajib diisi.',
            'service.numeric' => 'Biaya service harus berupa angka.',
            'service.min' => 'Biaya service tidak boleh negatif.',

            'description.max' => 'Deskripsi maksimal 255 karakter.',
        ];
    }
}
