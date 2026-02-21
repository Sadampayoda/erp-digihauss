<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
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
}
