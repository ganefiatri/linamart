<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

/**
 * Product Category Models
 * @property string $title
 * @property string $slug
 * @property array $meta
 */
class ProductCategory extends Model
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
        'title',
        'slug',
        'description',
        'meta'
    ];

    /**
     * ProductCategory to Product relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    /**
     * ProductCategory to Shop relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get image
     *
     * @param integer $width
     * @param integer $height
     * @param array $html_options
     * @param boolean $fixed_crop
     * @return void
     */
    public function getImage($width = 100, $height = 100, $html_options = [], $fixed_crop = false)
    {
        $img_options = '';
        foreach ($html_options as $i => $html_option) {
            $img_options .= $i . '="' . $html_option . '"';
        }

        if (is_array($meta = $this->meta)) {
            $src = storage_path('app/public/' . $meta['path'] . '/' . $meta['file_name']);
            if (file_exists($src)) {
                $img = Image::make($src);
                if (!$fixed_crop) {
                    $img->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                } else {
                    $img->resize($width, $height);
                }
    
                $response = $img->response('png', 70);
                if ($response->status() == 200) {
                    echo '<img src="data:image/png;base64,' .
                    base64_encode($response->content()) . '" ' . $img_options . ' />';
                } else {
                    $size = max($width, $height);
                    if (empty($size)) {
                        $size = 100;
                    }
                    echo '<img src="https://via.placeholder.com/' . $size . '.webp" ' . $img_options . '>';
                }
            } else {
                echo '<img src="https://via.placeholder.com/' . max($width, $height) . '.webp" ' . $img_options . '>';
            }
        } else {
            $size = max($width, $height);
            if (empty($size)) {
                $size = 100;
            }
            echo '<img src="https://via.placeholder.com/' . $size . '.webp" ' . $img_options . '>';
        }
    }
}
