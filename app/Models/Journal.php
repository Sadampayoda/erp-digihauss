<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'journal_number',
        'journal_date',
        'journal_type',
        'journal_action',
        'reference_number',
        'contact',
        'description',
        'total_debit',
        'total_credit',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function details()
    {
        return $this->hasMany(JournalDetail::class, 'journal_id');
    }
}
