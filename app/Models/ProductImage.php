<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

/**
 * @property integer $product_id
 * @property string $path
 * @property string $file_name
 * @property integer $is_default
 * @property array $meta
 */
class ProductImage extends Model
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
        'product_id',
        'path',
        'file_name',
        'is_default',
        'meta'
    ];

    /**
     * Get thumnail
     *
     * @param integer $width
     * @param integer $height
     * @param array $html_options
     * @param boolean $fixed_crop
     * @return void
     */
    public function getThumbnail($width = 100, $height = 100, $html_options = [], $fixed_crop = false)
    {
        $img_options = '';
        foreach ($html_options as $i => $html_option) {
            $img_options .= $i . '="' . $html_option . '"';
        }

        $src = storage_path('app/public' . DIRECTORY_SEPARATOR . $this->path . DIRECTORY_SEPARATOR . $this->file_name);
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
                echo '<img src="data:image/png;base64,'.
                    base64_encode($response->content()) . '" ' . $img_options . ' />';
            } else {
                $size = max($width, $height);
                if (empty($size)) {
                    $size = 100;
                }
                echo '<img src="https://via.placeholder.com/' . $size . '.webp" ' . $img_options . '>';
            }
        } else {
            $size = max($width, $height);
            echo '<img src="https://via.placeholder.com/' . $size . '.webp" ' . $img_options . '>';
        }
    }
}
