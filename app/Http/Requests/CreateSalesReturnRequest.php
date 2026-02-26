<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSalesReturnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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

            'sales_invoice_id' => [
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

            'items'                     => ['required', 'array', 'min:1'],
            'items.*.detail_id'           => ['nullable'],
            'items.*.advance_sale_items_id'=> ['nullable'],
            'items.*.item_id'                => ['required', 'integer', 'exists:items,id'],
            'items.*.sale_price'        => ['nullable', 'numeric', 'min:0'],
            'items.*.purchase_price'    => ['nullable', 'numeric', 'min:0'],
            'items.*.quantity'          => ['nullable', 'integer', 'min:1'],
            'items.*.si_quantity'          => ['nullable', 'integer'],
            'items.*.service'           => ['nullable', 'numeric', 'min:0'],
            'items.*.sub_total'         => ['nullable', 'numeric', 'min:0'],
            'items.*.margin'            => ['nullable', 'numeric'],
            'items.*.margin_percentage' => ['nullable', 'numeric'],

        ];
    }
}
