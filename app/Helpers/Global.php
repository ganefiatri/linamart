<?php

use App\Models\District;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\ProductUnit;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

if (!function_exists('to_money_format')) {
    /**
     * Convert number to money format
     *
     * @param double|integer $number
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    function to_money_format($number, $prefix = 'Rp ', $suffix = ''): string
    {
        $format = number_format($number, 0, ',', '.');
        return $prefix . $format . $suffix;
    }
}

if (!function_exists('wa_url')) {
    /**
     * Convert phone number to whatsapp url
     *
     * @param string $phone_number
     * @return string
     */
    function wa_url($phone_number): string
    {
        if (empty($phone_number)) {
            return '';
        }

        $number = filter_var($phone_number, FILTER_SANITIZE_NUMBER_INT);
        if ($number !== false) {
            // check if still any + or - sign
            $number = str_replace(['-', '+'], "", $number);
            $chars = preg_split('//', $number, -1, PREG_SPLIT_NO_EMPTY);
            if (is_array($chars) && count($chars) > 0 && $chars[0] == 0) {
                $chars[0] = 62;
                $number = implode("", $chars);
            }
        }

        return 'https://wa.me/' . $number;
    }
}

if (!function_exists('phone_url')) {
    /**
     * Convert phone number to tel url
     *
     * @param string $phone_number
     * @return string
     */
    function phone_url($phone_number): string
    {
        if (empty($phone_number)) {
            return '';
        }

        $number = filter_var($phone_number, FILTER_SANITIZE_NUMBER_INT);
        if ($number !== false) {
            // check if still any + or - sign
            $number = str_replace(['-', '+'], "", $number);
            $chars = preg_split('//', $number, -1, PREG_SPLIT_NO_EMPTY);
            if (is_array($chars) && count($chars) > 0 && $chars[0] == 0) {
                $chars[0] = '+62';
                $number = implode("", $chars);
            }
        }

        return 'tel:' . $number;
    }
}

if (!function_exists('notif_counter')) {
    /**
     * Notification counter
     *
     * @return int
     */
    function notif_counter(): int
    {
        if (!Auth::check()) {
            return 0;
        }

        $user = Auth::user();

        return Notification::where([
            'user_id' => ($user instanceof \App\Models\User) ? $user->id : 0,
            'status' => 0
        ])->count();
    }
}

if (!function_exists('assignment_counter')) {
    /**
     * Assignment counter
     *
     * @return int
     */
    function assignment_counter(): int
    {
        if (!Auth::check()) {
            return 0;
        }

        $user = Auth::user();
        $driver = null;
        if ($user instanceof \App\Models\User) {
            $driver = $user->driver;
        }

        if (!$driver instanceof \App\Models\Driver) {
            return 0;
        }

        $count = Invoice::whereHas('orderProcesses', function ($q) use ($driver) {
            $q->where('status', 2);
            $q->where('driver_id', $driver->id);
        })
            ->whereDoesntHave('orderProcesses', function ($q) {
                $q->where('status', '>', 2);
                $q->orWhere('status', '<', 0);
            })->count();

        return $count;
    }
}

if (!function_exists('get_image')) {
    /**
     * Get image
     *
     * @param string $src
     * @param integer $width
     * @param integer $height
     * @param array $html_options
     * @param boolean $fixed_crop
     * @return void
     */
    function get_image($src = null, $width = 100, $height = 100, $html_options = [], $fixed_crop = false)
    {
        $img_options = '';
        foreach ($html_options as $i => $html_option) {
            $img_options .= $i . '="' . $html_option . '"';
        }

        $full_src = storage_path('app/public/' . $src);
        $img = \Intervention\Image\Facades\Image::make($full_src);
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
        }
    }
}

if (!function_exists('get_districts')) {
    /**
     * Assignment counter
     *
     * @return array
     */
    function get_districts(): array
    {
        $districts = Cache::get('districts', []);
        if (!is_array($districts) || (is_array($districts) && count($districts) <= 0)) {
            $districts = Cache::rememberForever('districts', function () {
                $district = new District();
                return $district->getListDistricts();
            });
        }

        return $districts;
    }
}

if (!function_exists('get_product_units')) {
    /**
     * List of product units
     *
     * @return array
     */
    function get_product_units(): array
    {
        return ProductUnit::orderBy('title', 'asc')
            ->pluck('title', 'code')
            ->toArray();
    }
}

if (!function_exists('is_using_district')) {
    /**
     * Check is using district id
     *
     * @return bool
     */
    function is_using_district(): bool
    {
        return ((int) config('global.required_district_id') > 0);
    }
}

if (!function_exists('get_district_value')) {
    /**
     * Get District value
     *
     * @param \App\Models\Member|\App\Models\Shop|\App\Models\Driver $model|null
     * @return string
     */
    function get_district_value($model = null): string
    {
        if (empty($model)) {
            return '';
        }

        $district_name = $model->district_name ?? '';
        if (empty($district_name) && $model->district_id > 0) {
            $districts = get_districts();
            $district_name = $districts[$model->district_id] ?? $district_name;
        }

        return $district_name;
    }
}

if (!function_exists('is_allow_tracking')) {
    /**
     * Is robots and analytics allowed
     *
     * @return bool
     */
    function is_allow_tracking(): bool
    {
        $memberPage = false;
        if (!empty($currentRoute = Route::current())) {
            $routePrefix = $currentRoute->action['prefix'] ?? '';
            $memberPage = in_array($routePrefix, ['member', 'driver', '/member', '/driver']);
        }
        $visitor_tracking = ((int) config('global.visitor_tracking') > 0);
        return ($visitor_tracking && $memberPage);
    }
}

if (!function_exists('shop_open_info')) {
    /**
     * Check is shop open
     *
     * @param \App\Models\Shop $shop
     * @return array
     */
    function shop_open_info($shop)
    {
        $result = ['is_open' => true, 'today' => ''];
        if (!empty($daily_open = $shop->meta['daily_open'] ?? null)) {
            $day_open = $daily_open[date('w') - 1] ?? null;
            if (!empty($day_open)) {
                $_open = $day_open['open'] ?? '00:00';
                $_closed = $day_open['closed'] ?? '00:00';

                $date1 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d') .' '. $_open .':00');
                $date2 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d') .' '. $_closed .':00');
                // case open 14:00 and closed 03:00
                if ($date2 && !$date2->gt($date1)) {
                    $date2->addDays(1);
                }
                $dateNow = Carbon::now();
                $result['today'] = 'Tutup ' . $_open .' - '. $_closed;
                if ($dateNow->gte($date1) && $dateNow->lte($date2)) {
                    $result['today'] = 'Buka ' . $_open .' - '. $_closed;
                }
            }

            $result['daily_open'] = $daily_open;
        }

        return $result;
    }
}

if (!function_exists('get_closed_shops')) {
    /**
     * get current closed shops
     *
     * @return array
     */
    function get_closed_shops()
    {
        $shopIds = [];
        Shop::where('status', 1)
            ->where('meta', 'like', '%"day_name":"'. date('l') .'"%')
            ->limit(500)
            ->each(function ($shop) use (&$shopIds) {
                $daily_open = collect($shop->meta['daily_open'] ?? []);
                $current_day = $daily_open->where('day_name', date('l'))->first();
                if (is_array($current_day) && array_key_exists('open', $current_day)) {
                    $_open = $current_day['open'] ?? '00:00';
                    $_closed = $current_day['closed'] ?? '00:00';
                    $date1 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d') .' '. $_open .':00');
                    $date2 = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d') .' '. $_closed .':00');
                    // case open 14:00 and closed 03:00
                    if ($date2 && !$date2->gt($date1)) {
                        $date2->addDays(1);
                    }
                    $dateNow = Carbon::now();
                    if ($dateNow->gte($date2) || $dateNow->lte($date1)) {
                        array_push($shopIds, $shop->id);
                    }
                }
            });
        return $shopIds;
    }
}
