<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $stats = [
            'total_products' => Product::where(['active' => 1, 'enabled' => 1])->count(),
            'total_orders' => Invoice::where('status', 1)->count(),
            'total_sales' => Invoice::where('status', 1)->sum('base_income'),
            'total_shop' => Invoice::where('status', 1)->count(),
            'total_members' => Member::where('status', 1)->count(),
            'total_drivers' => Driver::where('status', 1)->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
