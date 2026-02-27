<?php

namespace App\Http\Requests;

use App\Models\SettingCoa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSettingCoaRequest extends FormRequest
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

        $id = $this->route('setting_coa');
        $settingCoa = SettingCoa::class;
        // route model binding atau parameter id
        return [
            'module' => [
                'required',
                'string',
                Rule::in(array_keys($settingCoa::$module)),
            ],

            'action' => [
                'required',
                'string',
                Rule::in(array_keys($settingCoa::$action)),
            ],

            'payment_method' => [
                'nullable',
                'integer',
                'exists:payment_methods,id',
            ],

            'coa_id' => [
                'required',
                'integer',
                'exists:coas,id',
            ],

            'position' => [
                'required',
                Rule::in(['debit', 'credit']),
            ],

            'description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            /**
             *
             * (module + action + payment_method harus unik)
             */
            Rule::unique('setting_coas')
                ->where(
                    fn($q) => $q
                        ->where('module', $this->module)
                        ->where('action', $this->action)
                        ->where('payment_method', $this->payment_method)
                )
                ->ignore($id),
        ];
    }

    public function messages(): array
    {
        return [
            'module.required' => 'Fitur wajib dipilih.',
            'action.required' => 'Aksi wajib dipilih.',
            'coa_id.required' => 'COA wajib dipilih.',
            'position.required' => 'Posisi jurnal wajib ditentukan.',
            'position.in' => 'Posisi jurnal harus debit atau kredit.',
            'payment_method.exists' => 'Metode pembayaran tidak valid.',
        ];
    }
}
