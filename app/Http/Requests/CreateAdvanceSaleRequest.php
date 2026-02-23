<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdvanceSaleRequest extends FormRequest
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
            'customer'          => ['required', 'integer', 'exists:contacts,id'],
            'transaction_date'  => ['required', 'date'],
            'sales'             => ['required', 'integer', 'exists:contacts,id'],
            'payment_method'    => ['required', 'integer'],
            'status'            => ['required'],
            'description'       => ['nullable', 'string', 'max:255'],
            'advance_amount'    => ['nullable', 'numeric', 'min:0'],
            'item_quantity'     => ['nullable', 'integer', 'min:1'],
            'sub_total'         => ['nullable', 'numeric', 'min:0'],
            'purchase_price'    => ['nullable', 'numeric', 'min:0'],
            'service'           => ['nullable', 'numeric', 'min:0'],
            'margin'            => ['nullable', 'numeric'],
        'margin_percentage' => ['nullable', 'numeric'],

            'items'                     => ['required', 'array', 'min:1'],
            'items.*.detail_id'           => ['nullable'],
            'items.*.item_id'                => ['required', 'integer', 'exists:items,id'],
            'items.*.name'              => ['required', 'string', 'max:255'],
            'items.*.variant'           => ['nullable', 'string', 'max:255'],

            'items.*.sale_price'        => ['nullable', 'numeric', 'min:0'],
            'items.*.purchase_price'    => ['nullable', 'numeric', 'min:0'],
            'items.*.quantity'          => ['nullable', 'integer', 'min:1'],
            'items.*.service'           => ['nullable', 'numeric', 'min:0'],
            'items.*.sub_total'         => ['nullable', 'numeric', 'min:0'],
            'items.*.margin'            => ['nullable', 'numeric'],
            'items.*.margin_percentage' => ['nullable', 'numeric'],
        ];
    }
}
