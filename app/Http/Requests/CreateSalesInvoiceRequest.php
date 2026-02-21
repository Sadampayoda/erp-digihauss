<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSalesInvoiceRequest extends FormRequest
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
            'transaction_date' => [
                'required',
                'date',
            ],

            'advance_sale_id' => [
                'nullable',
                'integer',
                'exists:advance_sales,id',
            ],

            'customer' => [
                'required',
                'integer',
                'exists:contacts,id',
            ],

            'sales' => [
                'nullable',
                'integer',
                'exists:contacts,id',
            ],

            'sub_total' => [
                'required',
                'numeric',
                'min:0',
            ],

            'service' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'grand_total' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'paid_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'remaining_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'payment_method' => [
                'nullable',
                'integer',
                'exists:payment_methods,id',
            ],

            'coa_id' => [
                'nullable',
                'integer',
                'exists:coas,id',
            ],

            'status' => [
                'required',
                'integer',
            ],

            'description' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sub_total'        => $this->sub_total ?? 0,
            'service'          => $this->service ?? 0,
            'discount'         => $this->discount ?? 0,
            'paid_amount'      => $this->paid_amount ?? 0,
            'remaining_amount' => $this->remaining_amount ?? 0,
        ]);
    }

    public function messages(): array
    {
        return [
            'transaction_number.unique' => 'Nomor transaksi sudah digunakan.',
            'paid_amount.min'           => 'Pembayaran tidak boleh bernilai negatif.',
            'remaining_amount.min'      => 'Sisa pembayaran tidak boleh bernilai negatif.',
        ];
    }
}
