<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Intervention\Image\Facades\Image;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'member_id',
        'driver_id',
        'status',
        'meta'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'meta' => 'array'
    ];

    /**
     * User to Member relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * User to Driver relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get status in string format
     *
     * @return string
     */
    public function getStatus()
    {
        $statusList = Lookup::where('type', 'UserStatus')
            ->orderBy('position')
            ->pluck('name', 'code')
            ->toArray();

        return $statusList[$this->status];
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
