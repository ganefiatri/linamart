<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
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
        'name',
        'slug',
        'phone',
        'district_id',
        'district_name',
        'address',
        'postal_code',
        'meta',
        'status'
    ];

    /**
     * Shop to Member relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Shop to District relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Shop to Product relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'shop_id', 'id');
    }

    /**
     * Get status in string format
     *
     * @return string
     */
    public function getStatus()
    {
        $statusList = Lookup::where('type', 'ShopStatus')
        ->orderBy('position')
        ->pluck('name', 'code')
        ->toArray();

        return $statusList[$this->status];
    }
}
