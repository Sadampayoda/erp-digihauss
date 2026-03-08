<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCashRequest extends FormRequest
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
            'transaction_number' => 'nullable|string|max:50',

            'transaction_date' => 'required|date',

            'coa_debit' => 'required|exists:coas,id',

            'coa_credit' => 'required|exists:coas,id',

            'status' => 'required|string',
            'type' => [
                'required',
                Rule::in(['in', 'out']),
            ],



            'paid_amount' => 'required|numeric|min:0',

            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.date' => 'Format tanggal tidak valid.',

            'type.required' => 'Tipe transaksi wajib ada.',
            'type.in' => 'Tipe transaksi tidak valid.',

            'coa_debit.required' => 'Kas tujuan wajib dipilih.',
            'coa_debit.exists' => 'Kas tujuan tidak valid.',

            'coa_credit.required' => 'Sumber dana wajib dipilih.',
            'coa_credit.exists' => 'Sumber dana tidak valid.',

            'status.required' => 'Status wajib dipilih.',

            'paid_amount.required' => 'Jumlah pembayaran wajib diisi.',
            'paid_amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'paid_amount.min' => 'Jumlah pembayaran tidak boleh negatif.',
        ];
    }
}
