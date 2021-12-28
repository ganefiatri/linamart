<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Driver extends Model
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
        'name',
        'email',
        'email_verified_at',
        'phone',
        'address',
        'district_id',
        'district_name',
        'postal_code',
        'gender',
        'currency',
        'lang',
        'notes',
        'status',
        'meta'
    ];

    /**
     * Driver to User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Driver to OrderProcess relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProcesses()
    {
        return $this->hasMany(OrderProcess::class, 'driver_id', 'id');
    }

    /**
     * Driver to District relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get status in string format
     *
     * @return string
     */
    public function getStatus()
    {
        $statusList = Lookup::where('type', 'ClientStatus')
        ->orderBy('position')
        ->pluck('name', 'code')
        ->toArray();

        return $statusList[$this->status];
    }

    /**
     * Get gender in string format
     *
     * @return string
     */
    public function getGender()
    {
        $genderList = Lookup::where('type', 'Gender')
        ->orderBy('position')
        ->pluck('name', 'code')
        ->toArray();

        return $genderList[$this->gender];
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

        if (is_array($meta = $this->meta) && array_key_exists('path', $meta)) {
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
                $size = max($width, $height);
                echo '<img src="https://via.placeholder.com/' . $size . '.webp" ' . $img_options . '>';
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
