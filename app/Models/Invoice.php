<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $shop_id
 * @property integer $member_id
 * @property string $serie
 * @property integer $nr
 * @property integer $status
 */
class Invoice extends Model
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
        'shop_id',
        'member_id',
        'serie',
        'nr',
        'hash',
        'currency',
        'currency_rate',
        'credit',
        'base_income',
        'base_refund',
        'refund',
        'shipping_id',
        'shipping_fee',
        'notes',
        'status',
        'seller_name',
        'seller_phone',
        'seller_address',
        'seller_city',
        'buyer_name',
        'buyer_phone',
        'buyer_address',
        'buyer_city',
        'buyer_postal_code',
        'due_at',
        'reminded_at',
        'paid_at',
        'refunded_at',
        'meta'
    ];

    /**
     * Invoice to Member relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    /**
     * Invoice to Shop relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Invoice to Orders relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Invoice to OrderProcess relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProcesses()
    {
        return $this->hasMany(OrderProcess::class, 'invoice_id', 'id');
    }

    /**
     * Invoice to OrderProcess relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastOrderProcess()
    {
        return $this->hasOne(OrderProcess::class, 'invoice_id', 'id')->orderBy('status', 'desc');
    }

    /**
     * Invoice to OrderProcess relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function maxOrderProcess()
    {
        return $this->hasOne(OrderProcess::class, 'invoice_id', 'id')->orderBy('status', 'desc');
    }

    /**
     * Invoice to Shipping relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    /**
     * Get status in string format
     *
     * @return string
     */
    public function getStatus()
    {
        $statusList = Lookup::where('type', 'InvoiceStatus')
        ->orderBy('position')
        ->pluck('name', 'code')
        ->toArray();

        return $statusList[$this->status];
    }

    /**
     * Get invoice formated number
     *
     * @param string $serie
     * @param integer $nr
     * @param integer $shop_id
     * @return string
     */
    public function getInvoiceNumber($serie = null, $nr = 0, $shop_id = 0)
    {
        if (empty($serie)) {
            $serie = $this->serie;
            $shop_id = $this->shop_id;
        }

        if ($nr == 0) {
            $nr = $this->nr;
        }

        if (empty($shop_id)) {
            $shop_id = $this->shop_id;
        }

        $pos = strpos($serie, '-');
        if ($pos === false) {
            $serie .= '-';
        }

        return $serie . $shop_id . str_repeat("0", 6 - strlen($nr . '')) . $nr;
    }

    /**
     * Get courier/driver
     *
     * @return \App\Models\Driver|null
     */
    public function getCourier()
    {
        $orderProcess = $this->orderProcesses()->where('driver_id', '>', 0)->first();
        return (!empty($orderProcess)) ? $orderProcess->driver : null;
    }

    /**
     * Delivered order information
     *
     * @return string|null
     */
    public function getDeliveredAt()
    {
        $orderProcess = $this->orderProcesses()
            ->where('driver_id', '>', 0)
            ->where('status', 3)
            ->first();
        return (!empty($orderProcess)) ? $orderProcess->updated_at : null;
    }

    /**
     * Complete order information
     *
     * @return string|null
     */
    public function getCompletedAt()
    {
        $orderProcess = $this->orderProcesses()
            ->select('created_at')
            ->where('status', 4)
            ->first();
        return (!empty($orderProcess)) ? $orderProcess->created_at : null;
    }

    /**
     * Process date information
     *
     * @param mixed $status
     * @return \Illuminate\Support\Carbon|array|null
     */
    public function getProcessDate($status = '')
    {
        $dates = $this->orderProcesses()
            ->select('status', 'created_at')
            ->pluck('created_at', 'status')
            ->toArray();

        return (strlen($status) > 0)? ($dates[$status] ?? null) : $dates;
    }
}
