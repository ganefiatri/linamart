<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'shop_id',
        'category_id',
        'title',
        'slug',
        'description',
        'unit',
        'weight',
        'price',
        'discount',
        'stock',
        'related_ids',
        'active',
        'enabled',
        'hidden',
        'priority',
        'meta',
        'viewed'
    ];

    /**
     * Product to Category relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    /**
     * Product to Shop relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Product to Order relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Product to Images relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Product to Review relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get price in currency format
     *
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function getFormatedPrice($prefix = 'Rp ', $suffix = ''): string
    {
        $format = number_format($this->price ?? 0, 0, ',', '.');
        return $prefix . $format . $suffix;
    }

    /**
     * Get net price (price - discount) in currency format
     *
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function getFormatedNetPrice($prefix = 'Rp ', $suffix = ''): string
    {
        $price = floatval($this->price ?? 0);
        $discount = floatval($this->discount ?? 0);
        $format = number_format(($price - $discount), 0, ',', '.');
        return $prefix . $format . $suffix;
    }

    /**
     * Get default image for product
     *
     * @param integer $width
     * @param integer $height
     * @param array $html_options
     * @param boolean $fixed_crop
     * @param integer $id
     * @return void
     */
    public function getDefaultImage($width = 150, $height = 150, $html_options = [], $fixed_crop = false, $id = 0)
    {
        $img_options = '';
        foreach ($html_options as $i => $html_option) {
            $img_options .= $i . '="' . $html_option . '"';
        }

        $product = $this;
        if ($id > 0) {
            $product = self::find($id);
        }

        $size = max($width, $height);
        if (empty($size)) {
            $size = 100;
        }
        if ($product instanceof \App\Models\Product) {
            $productImage = $product->images()->orderBy('is_default', 'desc')->first();
            if ($productImage instanceof \App\Models\ProductImage) {
                $productImage->getThumbnail($width, $height, $html_options, $fixed_crop);
            } else {
                echo '<img src="https://via.placeholder.com/'. $size .'.webp" '. $img_options .'>';
            }
        } else {
            echo '<img src="https://via.placeholder.com/'. $size .'.webp" '. $img_options .'>';
        }
    }

    /**
     * Product rate information
     *
     * @return array
     */
    public function rate()
    {
        $qry = $this->reviews()->where('status', 1);
        return [
            'value' => round($qry->avg('rating') ?? 0, 2),
            'count' => $qry->count()
        ];
    }

    /**
     * Get status in string format
     *
     * @return string
     */
    public function getStatus()
    {
        $statusList = Lookup::where('type', 'ProductStatus')
        ->orderBy('position')
        ->pluck('name', 'code')
        ->toArray();

        return $statusList[$this->active];
    }
}
