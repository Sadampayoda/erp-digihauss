<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

trait Validate
{
    public function existsWhereId($model, int $id, $with = [])
    {

        if (!$model) {
            throw new InvalidArgumentException(
                'Model tidak tersedia. Pastikan $this->model diset di controller atau kirim model sebagai parameter.'
            );
        }

        $exists = $model->find($id);

        if (! $exists) {
            throw new ModelNotFoundException("Data dengan ID {$id} tidak ditemukan");
        }

        if(!empty($with)) {
            $exists->load($with);
        }

        return $exists;
    }

    public function canDelete($can = [])
    {
        if (!$this->status) {
            throw new InvalidArgumentException(
                'Status tidak tersedia'
            );
        }
        $canDelete = collect($can ?? [0, 1, 2])->contains($this->status);
        $transactionStatus = transactionStatus('transaction');
        if(!$canDelete) {
            throw ValidationException::withMessages([
                'advance_amount' => 'Data tidak bisa di hapus karena status '.$transactionStatus[$this->status],
            ]);
        }

    }
}
