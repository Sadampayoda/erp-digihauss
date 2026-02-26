<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'journal_id',
        'coa',
        'coa_name',
        'debit',
        'credit',
        'description',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }
}
