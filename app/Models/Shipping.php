<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
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
        'title',
        'distance_from',
        'distance_to',
        'cost',
        'description',
        'meta',
        'enabled'
    ];

    /**
     * Shipping to Order relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get cost in currency format
     *
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function getFormatedCost($prefix = 'Rp ', $suffix = ''): string
    {
        $format = number_format($this->cost ?? 0, 0, ',', '.');
        return $prefix . $format . $suffix;
    }
}
