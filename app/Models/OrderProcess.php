<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProcess extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'invoice_id',
        'status',
        'notes',
        'user_id',
        'driver_id'
    ];

    /**
     * OrderProcess to Invoice relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    /**
     * OrderProcess to User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * OrderProcess to Driver relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }

    /**
     * Get status in string format
     *
     * @return string
     */
    public function getStatus()
    {
        $statusList = Lookup::where('type', 'OrderStatus')
        ->orderBy('position')
        ->pluck('name', 'code')
        ->toArray();

        return $statusList[$this->status];
    }
}
