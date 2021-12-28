<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

/**
 * @property integer $member_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property integer $district_id
 * @property string $district_name
 * @property string $postal_code
 * @property integer $gender
 * @property integer $status
 */
class Member extends Model
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
        'email',
        'email_verified_at',
        'group_id',
        'phone',
        'address',
        'district_id',
        'district_name',
        'postal_code',
        'gender',
        'currency',
        'lang',
        'notes',
        'meta',
        'status'
    ];

    /**
     * Member to Group relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(MemberGroup::class);
    }

    /**
     * Member to Shop relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    /**
     * Member to District relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Member to User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Member to Order relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'member_id', 'id');
    }

    /**
     * Member to Balance relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function balances()
    {
        return $this->hasMany(MemberBalance::class, 'member_id', 'id');
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
     * Check member has completing his address information
     *
     * @return boolean
     */
    public function hasCompleteAddress(): bool
    {
        $has_district = (!empty($this->district_id) || !empty($this->district_name));
        if (empty($this->email) || empty($this->phone) || empty($this->address) || !$has_district) {
            return false;
        }

        return true;
    }
}
