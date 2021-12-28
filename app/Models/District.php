<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class District extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'postal_code',
        'city_id'
    ];

    /**
     * District to City relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * List district with it city and province
     *
     * @return array
     */
    public function getListDistricts(): array
    {
        $items = [];
        $storage_district = Storage::disk('public')->get('districts.json');
        if (empty($storage_district)) {
            $districts = self::all();
            foreach ($districts as $district) {
                $district_data = [$district->name];
                if (!empty($district->city)) {
                    array_push($district_data, $district->city->type .' '. $district->city->name);
                    if (!empty($district->city->province)) {
                        array_push($district_data, $district->city->province->name);
                    }
                }
                
                $items[$district->id] = implode(', ', $district_data);
            }

            $json_district_data = json_encode($items);
            if ($json_district_data === false) {
                $json_district_data = '';
            }

            Storage::disk('public')->put('districts.json', $json_district_data);
        } else {
            $items = json_decode($storage_district, true);
        }

        return $items;
    }
}
