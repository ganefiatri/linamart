<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $member_id
 * @property double $start_balance
 * @property double $end_balance
 * @property integer $invoice_id
 * @property string $last_sync
 * @property string $notes
 */
class MemberBalance extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'member_id',
        'start_balance',
        'end_balance',
        'invoice_id',
        'last_sync',
        'notes',
        'meta'
    ];

    /**
     * MemberBalance to Member relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * MemberBalance to Invoice relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
