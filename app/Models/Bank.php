<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Bank extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedDeletedBy;

    protected $fillable = [
        'transaction_number',
        'transaction_date',
        'type',
        'coa_debit',
        'coa_credit',
        'payment_method',
        'description',
        'paid_amount',
        'status',
        'created_by',
        'updated_by'
    ];

    public const TYPE_IN  = 'in';
    public const TYPE_OUT = 'out';

    public static array $type = [
        self::TYPE_IN  => 'Bank In',
        self::TYPE_OUT => 'Bank Out',
    ];

    public static array $typeDesc = [
        self::TYPE_IN  => 'Bank Masuk',
        self::TYPE_OUT => 'Bank Keluar',
    ];

    public static function recentBankAsset($limit = 3)
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            $dateFilter = "DATE_TRUNC('month', j.journal_date) = DATE_TRUNC('month', CURRENT_DATE)";
            $randomFunc = "RANDOM()";
        } else { // mysql
            $dateFilter = "DATE_FORMAT(j.journal_date,'%Y-%m') = DATE_FORMAT(CURDATE(),'%Y-%m')";
            $randomFunc = "RAND()";
        }

        return DB::select("
                SELECT
                    c.code,
                    c.name,
                    COALESCE(SUM(jd.debit - jd.credit),0) AS balance,
                    MAX(j.journal_date) AS last_transaction
                FROM coas c
                LEFT JOIN journal_details jd
                    ON c.code = jd.coa
                    AND jd.deleted_at IS NULL
                LEFT JOIN journals j
                    ON j.id = jd.journal_id
                    AND j.deleted_at IS NULL
                    AND $dateFilter
                WHERE
                    c.type = 'asset'
                    AND c.level = 3
                    AND c.is_active = true
                GROUP BY
                    c.code, c.name
                ORDER BY
                    CASE WHEN MAX(j.journal_date) IS NULL THEN 1 ELSE 0 END,
                    MAX(j.journal_date) DESC,
                    $randomFunc
                LIMIT ?
            ", [$limit]);
    }
}
