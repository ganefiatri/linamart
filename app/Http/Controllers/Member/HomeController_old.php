<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
     * Show the application home.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index()
    {
        balance_sync(); // check balance from API before transaction

        $member = $this->getMember();
        $shopId = 0;
        if ($member === null) {
            abort(401);
        } else {
            if ($member->shop instanceof \App\Models\Shop) {
                $shopId = $member->shop->id;
            }
        }

        $categories = ProductCategory::orderBy('title', 'desc')
            ->limit(20)
            ->get();

        $closedShops = get_closed_shops();
        array_push($closedShops, $shopId);
        $latestProducts = Product::whereNotIn('shop_id', $closedShops)
            ->whereHas('shop', function ($q) {
                $q->where('status', 1);
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $shops = Cache::remember("shops{$shopId}", 300, function () use ($closedShops) {
            return Shop::orderBy('created_at', 'desc')
                ->where('status', 1)
                ->whereNotIn('id', $closedShops)
                ->whereHas('products')
                ->limit(20)
                ->get();
        });

        $totActiveProduct = Product::where(['active' => 1, 'enabled' => 1])->count();

        $favorite_product_by = config('global.favorite_product_by') ?? 'best_seller';
        $bestProducts = collect([]);
        if ($favorite_product_by == 'best_seller') {
            $bestProducts = Cache::remember(
                "bestProducts{$shopId}",
                3600,
                function () use ($closedShops) {
                    $productsIds = DB::table('orders')
                        ->select('product_id', DB::raw('count(*) as total'))
                        ->whereNotIn('shop_id', $closedShops)
                        ->groupBy('product_id')
                        ->orderBy('total', 'DESC')
                        ->limit(20)
                        ->pluck('product_id')
                        ->toArray();
    
                    $sort = implode(',', $productsIds);
                    return Product::whereIn('id', $productsIds)
                        ->orderByRaw("FIELD(id, $sort)")
                        ->get();
                }
            );
        } else {
            $bestProducts = Product::whereNotIn('shop_id', $closedShops)
                ->whereHas('shop', function ($q) {
                    $q->where('status', 1);
                })
                ->orderBy('priority', 'desc')
                ->limit(20)
                ->get();
        }

        return view('member.home', compact(
            'categories',
            'latestProducts',
            'shops',
            'totActiveProduct',
            'bestProducts'
        ));
    }

    /**
     * Show the application dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function dashboard(Request $request)
    {
        $member = $this->getMember();
        if ($member === null) {
            abort(401);
        }

        $products = [];
        $invoices = [];
        $orders = [];

        $this->searchRequest($request, $member, $products, $invoices, $orders);

        $total_sales = 0;
        if (count($invoices) > 0 && ($member->shop instanceof \App\Models\Shop)) {
            $total_sales = Invoice::where('shop_id', $member->shop->id)
                ->where('status', 1)
                ->sum('base_income');
        }

        $total_orders = 0;
        if (count($orders) > 0) {
            $total_orders = Invoice::where('member_id', $member->id)
                ->where('status', 1)
                ->sum('base_income');
        }

        $stats = [
            'total_sales' => $total_sales,
            'total_orders' => $total_orders
        ];

        $hasShop = ($member->shop instanceof \App\Models\Shop);

        $showFooter = true;

        return view(
            'member.dashboard',
            compact('products', 'invoices', 'orders', 'stats', 'showFooter', 'hasShop', 'member')
        );
    }

    /**
     * Handle search request
     *
     * @param Request $request
     * @param Member $member
     * @param array $products
     * @param array $invoices
     * @param array $orders
     * @return boolean
     */
    private function searchRequest(Request $request, Member $member, &$products, &$invoices, &$orders)
    {
        if (null !== $request->input('qp')) {
            $products = Product::where('title', 'like', '%' . $request->input('qp') . '%')
                ->whereHas('shop', function ($q) use ($member) {
                    $q->where('member_id', $member->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $products = Product::whereHas('shop', function ($q) use ($member) {
                $q->where('member_id', $member->id);
            })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        if (null !== $request->input('qi')) {
            $invoices = Invoice::where('title', 'like', '%' . $request->input('qi') . '%')
                ->whereHas('shop', function ($q) use ($member) {
                    $q->where('member_id', $member->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $invoices = Invoice::whereHas('shop', function ($q) use ($member) {
                $q->where('member_id', $member->id);
            })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        if (null !== $request->input('qo')) {
            $orders = Invoice::where('nr', 'like', '%' . $request->input('qi') . '%')
                ->where('member_id', $member->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $orders = Invoice::where('member_id', $member->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return true;
    }

    /**
     * Get member object
     *
     * @return \App\Models\Member|null
     */
    private function getMember()
    {
        $user = Auth::user();
        if ($user !== null) {
            return $user->member;
        }

        return null;
    }
}
