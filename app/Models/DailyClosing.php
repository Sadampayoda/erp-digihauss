<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyClosing extends Model
{
    protected $fillable = [
        'transaction_date',
        'user_id',

        'total_sales',
        'total_payment',
        'total_hpp',
        'total_profit',
        'total_quantity',

        'cash_expected',
        'cash_actual',
        'cash_difference',

        'total_stock_expected',
        'total_stock_actual',
        'total_stock_difference',

        'status',
        'closed_at',
        'closed_by',

        'is_locked',
        'locked_at',

        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUserNameAttribute()
    {
        return $this->user?->name ?? null;
    }

    public function dailyClosingItems()
    {
        return $this->hasMany(DailyClosingItem::class,'closing_day','id');
    }
    public function dailyClosingResponsibility()
    {
        return $this->hasMany(DailyClosingResponsibility::class,'closing_day','id');
    }
}
