<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAtkRequest extends FormRequest
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
        $id = $this->route('atk_request');

        return [

            'employee_id' => ['required', 'exists:users,id'],

            'transaction_date' => ['required', 'date'],

            'requested_fulfillment_date' => ['nullable', 'date', 'after_or_equal:today'],

            'payment_method' => ['required', 'exists:payment_methods,id'],

            'status' => ['required', 'integer'],

            'purpose' => ['nullable', 'string'],

            'grand_total' => ['required', 'numeric', 'min:0'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.item_id' => ['nullable', 'exists:item,id'],

            'items.*.detail_id' => ['nullable', 'exists:item_details,id'],

            'items.*.name' => ['required', 'string', 'max:255'],

            'items.*.price' => ['required', 'numeric', 'min:0'],

            'items.*.quantity_requested' => ['required', 'integer', 'min:1'],

            'items.*.quantity_approved' => ['nullable', 'integer', 'min:0'],

            'items.*.unit' => ['nullable', 'string', 'max:50'],

            'items.*.unit_id' => ['nullable', 'exists:units,id'],

            'items.*.sub_total' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Karyawan wajib dipilih.',
            'employee_id.exists' => 'Karyawan tidak valid.',

            'transaction_date.required' => 'Tanggal transaksi wajib diisi.',
            'transaction_date.date' => 'Format tanggal transaksi tidak valid.',

            'requested_fulfillment_date.date' => 'Format tanggal Kebutuhan tidak valid.',
            'requested_fulfillment_date.after_or_equal' => 'Tanggal Kebutuhan tidak boleh sebelum hari ini.',

            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method.exists' => 'Metode pembayaran tidak valid.',

            'status.required' => 'Status wajib diisi.',
            'status.integer' => 'Status harus berupa angka.',

            'purpose.string' => 'Tujuan harus berupa teks.',

            'grand_total.required' => 'Grand total wajib diisi.',
            'grand_total.numeric' => 'Grand total harus berupa angka.',
            'grand_total.min' => 'Grand total tidak boleh kurang dari 0.',

            'items.required' => 'Item wajib diisi minimal 1.',
            'items.array' => 'Format item tidak valid.',
            'items.min' => 'Minimal harus ada 1 item.',

            'items.*.item_id.exists' => 'Item tidak valid.',
            'items.*.detail_id.exists' => 'Detail item tidak valid.',

            'items.*.name.required' => 'Nama item wajib diisi.',
            'items.*.name.max' => 'Nama item maksimal 255 karakter.',

            'items.*.price.required' => 'Harga wajib diisi.',
            'items.*.price.numeric' => 'Harga harus berupa angka.',
            'items.*.price.min' => 'Harga tidak boleh kurang dari 0.',

            'items.*.quantity_requested.required' => 'Quantity request wajib diisi.',
            'items.*.quantity_requested.integer' => 'Quantity request harus berupa angka.',
            'items.*.quantity_requested.min' => 'Quantity request minimal 1.',

            'items.*.quantity_approved.integer' => 'Quantity approved harus berupa angka.',
            'items.*.quantity_approved.min' => 'Quantity approved tidak boleh kurang dari 0.',

            'items.*.unit.max' => 'Satuan maksimal 50 karakter.',

            'items.*.unit_id.exists' => 'Satuan tidak valid.',

            'items.*.sub_total.required' => 'Subtotal wajib diisi.',
            'items.*.sub_total.numeric' => 'Subtotal harus berupa angka.',
            'items.*.sub_total.min' => 'Subtotal tidak boleh kurang dari 0.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->status == 2) {

                foreach ($this->items as $index => $item) {

                    $approved = $item['quantity_approved'] ?? 0;
                    $requested = $item['quantity_requested'] ?? 0;

                    // wajib ada & minimal 1
                    if (is_null($approved) || $approved < 1) {
                        $validator->errors()->add(
                            "items.$index.quantity_approved",
                            'Quantity approved minimal 1 ketika status approved.'
                        );
                    }

                    // tidak boleh melebihi requested
                    if ($approved > $requested) {
                        $validator->errors()->add(
                            "items.$index.quantity_approved",
                            'Quantity approved tidak boleh melebihi quantity requested.'
                        );
                    }
                }
            }
        });
    }
}
