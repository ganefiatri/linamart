<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Shop;

class ShopController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shop  $shop
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Shop $shop)
    {
        return view('driver.shop.show', compact('shop'));
    }
}
