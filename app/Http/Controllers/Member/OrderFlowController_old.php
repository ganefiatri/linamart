<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Mail\OrderCreated;
use App\Models\District;
use App\Models\Invoice;
use App\Models\MailQueue;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Shipping;
use App\Models\Shop;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class   OrderFlowController extends Controller
{
    /**
     * Display product search results.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function searchProduct(Request $request)
    {
        $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        $shopId = 0;
        if ($member === null) {
            abort(401);
        } else {
            if (!$request->has('q') && $member->shop instanceof \App\Models\Shop) {
                $shopId = $member->shop->id;
            }
        }

        $qry = Product::query();
        if (empty($request->input('q'))) {
            $qry->whereHas('shop', function ($q) {
                $q->where('status', 1);
            });
        }

        $closedShops = get_closed_shops();
        if ($shopId > 0) {
            array_push($closedShops, $shopId);
        }

        if (!empty($request->input('q'))) {
            $qry->where('title', 'like', '%' . $request->input('q') . '%');
            $qry->orWhereHas('shop', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('q') . '%');
                $q->where('status', 1);
            });
        }

        if (count($closedShops) > 0) {
            $qry->whereNotIn('shop_id', $closedShops);
        }

        if (!empty($request->input('price_from'))) {
            $qry->where('price', '>=', $request->input('price_from'));
        }

        if (!empty($request->input('price_to'))) {
            $qry->where('price', '<=', $request->input('price_to'));
        }

        if (!empty($request->input('category'))) {
            $qry->where('category_id', $request->input('category'));
        }

        $products = $qry->orderBy('created_at', 'desc')->paginate(8);

        $categories = ProductCategory::orderBy('title', 'desc')
            ->limit(20)
            ->get();

        $totActiveProduct = $products->total();

        return view('member.orderflow.product_search', compact('products', 'categories', 'totActiveProduct'));
    }

    /**
     * Display product each category search results.
     *
     * @param  String  $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function categoryProduct(String $slug)
    {
        $products = Product::whereHas('category', function ($q) use ($slug) {
            $q->where('slug', $slug);
        })
            ->orderBy('created_at', 'desc')->paginate(8);

        $categories = ProductCategory::orderBy('title', 'desc')
            ->limit(20)
            ->get();

        $totActiveProduct = $products->total();

        return view('member.orderflow.product_search', compact('products', 'categories', 'totActiveProduct'));
    }

    /**
     * Display product each shop search results.
     *
     * @param  String  $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function shopProduct(String $slug)
    {
        $shop = Shop::where('slug', $slug)->where('status', 1)->firstOrFail();
        $products = Product::whereHas('shop', function ($q) use ($slug) {
            $q->where('slug', $slug);
        })
            ->orderBy('created_at', 'desc')->paginate(8);

        $totActiveProduct = Product::where(['active' => 1, 'enabled' => 1])->count();
        $categories = ProductCategory::orderBy('title', 'desc')
            ->limit(20)
            ->get();

        return view('member.orderflow.product_search', compact('products', 'totActiveProduct', 'categories'));
    }

    /**
     * Display product detail.
     *
     * @param  String  $slug
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function detailProduct(String $slug)
    {
        $product = Product::where('slug', $slug)
            ->whereHas('shop', function ($q) {
                $q->where('status', 1);
            })
            ->firstOrFail();
        $otherProducts = Product::where('shop_id', $product->shop_id)
            ->where('active', 1)
            ->where('enabled', 1)
            ->whereNotIn('id', [$product->id])
            ->limit(10)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($product instanceof \App\Models\Product) {
            $product_views = session('product_views', []);
            if (!in_array($product->id, $product_views)) {
                $viewed = $product->viewed + 1;
                $update = $product->update(['viewed' => $viewed]);
                if ($update) {
                    array_push($product_views, $product->id);
                    session(['product_views' => $product_views]);
                }
            }
        }

        return view('member.orderflow.product_detail', compact('product', 'otherProducts'));
    }

    /**
     * Add product to cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse.
     */
    protected function addToCart(Request $request, Product $product)
    {
        $cookie_cart = Cookie::get('cart') ?? '[]';
        $cookie = (is_array($cookie_cart)) ? $cookie_cart : json_decode($cookie_cart, true);
        $success = false;
        $message = __('Failed adding this product to cart');
        $totalCart = 0;
        $cartItems = null;
        $totalItems = 0;
        if ($request->has('qty') && ($product !== null)) {
            if (($qty = (int) $request->input('qty')) > 0) {
                $continue = true;
                // check product stock
                if ($product->stock <= 0) {
                    $message = __('This product is out of stock');
                    $continue = false;
                }
                // check the shop, only able to buy from 1 shop
                $cartShop = cart_shop();
                if (($cartShopId = $cartShop['id'] ?? 0) > 0) {
                    if ($product->shop_id != $cartShopId) {
                        $message = __('Please add product to cart only from');
                        if (is_string($message)) {
                            $message .= ' ' . ($cartShop['name'] ?? '');
                        }
                        $continue = false;
                    }
                } else {
                    $member = (!empty(Auth::user())) ? Auth::user()->member : null;
                    if (!empty($member)) {
                        $memberShop = $member->shop;
                        if (!empty($memberShop) && $memberShop->id == $product->shop_id) {
                            $message = __('Not allowed to add your own shop products');
                            $continue = false;
                        }
                    }
                }

                $oldQty = $cookie['items'][$product->id]['qty'] ?? 0;
                $cookie['items'][$product->id] = [
                    'title' => $product->title,
                    'slug' => $product->slug,
                    'category' => $product->category->title ?? null,
                    'category_slug' => $product->category->slug ?? null,
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'formated_price' => $product->getFormatedPrice(),
                    'formated_net_price' => $product->getFormatedNetPrice(),
                    'qty' => $qty,
                    'unit' => $product->unit,
                ];

                // check total cart with current balance
                $cartItemCollection = collect($cookie['items'] ?? []);
                $cartTotalWouldBe = cart_total($cartItemCollection);
                if (array_key_exists('total', $cartTotalWouldBe)) {
                    $balance = balance_info();
                    if ($cartTotalWouldBe['total'] >= $balance) {
                        $message = __('You dont have enough balance!');
                        $continue = false;
                    }
                }

                if ($continue) {
                    $cookie['shop'] = (!empty($product->shop)) ? $product->shop->toArray() : [];
                    if ($oldQty > $qty) {
                        $message = __('quantity successfully updated');
                    } else {
                        if ($qty > 1) {
                            $message = __('quantity successfully updated to');
                            if (is_string($message)) {
                                $message .= ' ' . $qty;
                            }
                        } else {
                            $message = __('successfully added to cart');
                        }
                    }
                    if (is_string($message)) {
                        $message = $product->title . ' ' . $message;
                    }
                    $success = true;
                } else {
                    if ($oldQty > 0) {
                        $cookie['items'][$product->id]['qty'] = $oldQty;
                    } else {
                        unset($cookie['items'][$product->id]);
                    }
                }
            } else {
                if (array_key_exists($product->id, ($cookie['items'] ?? []))) {
                    unset($cookie['items'][$product->id]);
                }
                // remove shop cart info if no items
                if (count($cookie['items']) <= 0) {
                    if (array_key_exists('shop', $cookie)) {
                        unset($cookie['shop']);
                    }
                }
                $message = __('successfully deleted from cart.');
                if (is_string($message)) {
                    $message = $product->title . ' ' .  $message;
                }
                $success = true;
            }
            Cookie::queue('cart', json_encode($cookie), 60);
            $cartItems = collect($cookie['items'] ?? []);
            $totalCart = $cartItems->sum('qty');
            $totalItems = $cartItems->count();
            if ($totalItems <= 0) {
                empty_cart();
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'total_cart_qty' => $totalCart,
            'total_cart_items' => $totalItems,
            'cart_total' => cart_total($cartItems)
        ]);
    }

    /**
     * Remove product from cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse.
     */
    protected function deleteCart(Request $request, Product $product)
    {
        $cookie_cart = Cookie::get('cart') ?? '[]';
        $cookie = (is_array($cookie_cart)) ? $cookie_cart : json_decode($cookie_cart, true);
        $success = false;
        $message = __('Failed to remove this product from cart');
        $totalCart = 0;
        $totalItems = 0;
        $cartItems = null;
        if ($product !== null && $request->has('id') && count($cookie['items'] ?? []) > 0) {
            if ($request->input('id') == $product->id) {
                if (array_key_exists($product->id, $cookie['items'])) {
                    $success = true;
                    unset($cookie['items'][$product->id]);
                    // remove shop cart info if no items
                    if (count($cookie['items']) <= 0) {
                        if (array_key_exists('shop', $cookie)) {
                            unset($cookie['shop']);
                        }
                    }
                    Cookie::queue('cart', json_encode($cookie), 60);
                    $message = __('successfully deleted from cart.');
                    if (is_string($message)) {
                        $message = $product->title . ' ' . $message;
                    }
                } else {
                    $message = __('Failed to delete from cart.');
                }
            } else {
                $message = __('Failed to delete from cart.');
            }

            $cartItems = collect($cookie['items']);
            $totalCart = $cartItems->sum('qty');
            $totalItems = $cartItems->count();
            if ($totalItems <= 0) {
                empty_cart();
            }
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'total_cart_qty' => $totalCart,
            'total_cart_items' => $totalItems,
            'cart_total' => cart_total($cartItems)
        ]);
    }

    /**
     * Display carts.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function carts()
    {
        $showFooter = true;
        $product = new Product();

        return view('member.orderflow.cart', compact('showFooter', 'product'));
    }

    /**
     * Display checkout page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function checkout()
    {
        $showFooter = true;

        $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        $districts = [];
        $district_name = '';
        if (is_using_district()) {
            $district = new District();
            $districts = $district->getListDistricts();
            if ($member !== null) {
                $district_name = ($member->district_id > 0) ? $districts[$member->district_id] : null;
            }
        } else {
            $district_name = get_district_value($member);
        }

        return view(
            'member.orderflow.checkout',
            compact('showFooter', 'member', 'district_name', 'districts')
        );
    }

    /**
     * Display payment page.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    protected function payment()
    {
        $showFooter = true;

        $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        if ($member instanceof \App\Models\Member) {
            if (!$member->hasCompleteAddress()) {
                return redirect()->back()->with('warning', __('Please complete your Address'));
            }

            if (str_contains($member->email, 'email.com') && (config('app.env') != 'local')) {
                return redirect()->back()->with('warning', __('Please provide valid email address'));
            }
        }

        $shop = cart_shop();
        $district = new District();
        $districts = $district->getListDistricts();
        $districtName = '';
        if (array_key_exists('district_id', $shop)) {
            $districtName = ($shop['district_id'] > 0) ? $districts[$shop['district_id']] : null;
        }

        $memberDistrictName = '';
        if ($member !== null) {
            $memberDistrictName = ($member->district_id > 0) ? $districts[$member->district_id] : null;
        }

        $shippings = Shipping::where('enabled', 1)
            ->OrderBy('distance_from', 'asc')->get();

        return view(
            'member.orderflow.payment',
            compact('showFooter', 'member', 'shop', 'districtName', 'memberDistrictName', 'shippings')
        );
    }

    /**
     * Add shipping method of cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse.
     */
    protected function shipping(Request $request)
    {
        $cookie_cart = Cookie::get('cart') ?? '[]';
        $cookie = (is_array($cookie_cart)) ? $cookie_cart : json_decode($cookie_cart, true);
        $success = false;
        $message = __('Failed to set shipping');
        $totalCart = 0;
        $cartItems = null;
        $totalItems = 0;
        $avoidShipping = false;
        if ($request->has('id')) {
            if ($request->input('id') > 0) {
                $shipping = Shipping::findOrFail($request->input('id'));
                if ($shipping !== null) {
                    // check is balance enough if has shipping
                    $cartItems = collect($cookie['items'] ?? []);
                    $cartTotalWouldBe = cart_total($cartItems, $shipping->toArray());
                    if (array_key_exists('total', $cartTotalWouldBe)) {
                        $balance = balance_info();
                        if ($cartTotalWouldBe['total'] > floatval($balance)) {
                            $message = __('You dont have enough balance!');
                            return response()->json([
                                'success' => false,
                                'message' => $message,
                                'total_cart_qty' => $cartItems->sum('qty'),
                                'total_cart_items' => $cartItems->count(),
                                'cart_total' => $cartTotalWouldBe['total']
                            ]);
                        }
                    }
                    // end of check balance
                    $cookie['shipping'] = $shipping->toArray();
                }
            } else {
                if (array_key_exists('shipping', $cookie)) {
                    $avoidShipping = true;
                    unset($cookie['shipping']);
                }
            }
            Cookie::queue('cart', json_encode($cookie), 60);
            $cartItems = collect($cookie['items'] ?? []);
            $totalCart = $cartItems->sum('qty');
            $totalItems = $cartItems->count();
            $success = true;
            $message = __('Successfully set the shipping');
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
            'total_cart_qty' => $totalCart,
            'total_cart_items' => $totalItems,
            'cart_total' => cart_total($cartItems, $cookie['shipping'] ?? null, $avoidShipping)
        ]);
    }

    /**
     * Proceed Payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function paymentProceed(Request $request)
    {
        $showFooter = true;

        $request->validate(
            [
                'delivery' => 'required',
                'shipping_id' => 'required_if:delivery,courier',
                'aggreement' => 'required'
            ],
            [
                'delivery.required' => 'Cara pengiriman tidak boleh dikosongi.',
                'shipping_id.required' => 'Ongkos kirim tidak boleh dikosongi.',
                'shipping_id.required_if' => 'Ongkos kirim tidak boleh dikosongi.',
                'aggreement.required' => 'Mohon cek form Persetujuan.',
            ]
        );

        if (!has_cart()) {
            abort(404);
        }

        // proceed order
        $cookie_cart = Cookie::get('cart') ?? '[]';
        $cart = (is_array($cookie_cart)) ? $cookie_cart : json_decode($cookie_cart, true);

        $cartShop = cart_shop();
        $member = (!empty(Auth::user())) ? Auth::user()->member : null;
        if ($member == null) {
            abort(401);
        }
        $cartTotal = cart_total();
        $sellerDistrict = District::find($cartShop['district_id']);
        $sellerCity = null;
        if ($sellerDistrict instanceof \App\Models\District) {
            $sellerCity = $sellerDistrict->city;
        }
        // use district_name if any
        $optionalSellerCity = $cartShop['district_name'] ?? null;
        $memberDistrict = $member->district;
        $memberCity = (!empty($memberDistrict)) ? $memberDistrict->city : null;
        // use district_name if any
        $optionalMemberCity = $member->district_name;
        $invoiceData = [
            'shop_id' => $cartShop['id'] ?? 0,
            'member_id' => $member->id,
            'base_income' => $cartTotal['total'] ?? 0,
            'shipping_fee' => $cartTotal['shipping_fee'] ?? 0,
            'shipping_id' => $request->input('shipping_id') ?? 0,
            'status' => 1,
            'seller_name' => $cartShop['name'],
            'seller_phone' => $cartShop['phone'],
            'seller_address' => $cartShop['address'],
            'seller_city' => ($sellerCity !== null) ? $sellerCity->name : $optionalSellerCity,
            'buyer_name' => $member->name,
            'buyer_phone' => $member->phone,
            'buyer_address' => $member->address,
            'buyer_city' => (!empty($memberCity)) ? $memberCity->name : $optionalMemberCity,
            'buyer_postal_code' => $member->postal_code,
            'meta' => $cart,
        ];

        $invoice = Invoice::create($invoiceData);
        if ($invoice !== null) {
            // add order data
            $cartItems = cart_items();
            $cartShipping = cart_shipping();
            $groupId = Order::max('group_id') + 1;
            $i = 0;
            foreach ($cartItems as $productId => $cartItem) {
                Order::create([
                    'shop_id' => $cartShop['id'],
                    'member_id' => $member->id,
                    'product_id' => $productId,
                    'group_id' => $groupId,
                    'group_master' => ($i == 0) ? 1 : 0,
                    'title' => $cartItem['title'],
                    'invoice_id' => $invoice->id,
                    'quantity' => $cartItem['qty'],
                    'unit' => $cartItem['unit'],
                    'price' => $cartItem['price'],
                    'discount' => $cartItem['discount'],
                    'shipping_id' => $cartShipping['id'] ?? 0,
                    'status' => 0
                ]);
                $i++;
            }
            empty_cart();

            // Add mail job
            $memberId = 0;
            if ($invoice->member instanceof \App\Models\Member) {
                // when failed communicate with api, invoice will be set to be unpaid
                if ($invoice->status > 0) {
                    MailQueue::create([
                        'mail_to' => $invoice->member->email,
                        'mail_class' => '\App\Mail\OrderCreated',
                        'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $invoice->id],
                        'priority' => 1
                    ]);
                }

                if ($invoice->member instanceof \App\Models\Member
                    && ($buyer_user = $invoice->member->user) instanceof \App\Models\User
                ) {
                    if ($invoice->status > 0) {
                        // Add notice to buyer
                        Notification::create([
                            'user_id' => $buyer_user->id,
                            'message' => 'Order Anda di toko ' . $invoice->seller_name
                                . ' dengan nomor order ' . $invoice->getInvoiceNumber()
                                . ' sedang menunggu diproses oleh Admin toko.',
                            'priority' => 3,
                            'meta' => [
                                'url' => route('member.invoice.show', $invoice->id),
                                'label' => __('Order Detail')
                            ]
                        ]);
                    }

                    $memberId = $invoice->member->id;
                }
            }

            if (($invoice->status > 0) && ($invoice->shop instanceof \App\Models\Shop)) {
                if ($invoice->shop->member instanceof \App\Models\Member) {
                    MailQueue::create([
                        'mail_to' => $invoice->shop->member->email,
                        'mail_class' => '\App\Mail\ShopIncomingOrder',
                        'mail_params' => ['model' => '\App\Models\Invoice', 'id' => $invoice->id],
                        'priority' => 1
                    ]);

                    if (($shop_user = $invoice->shop->member->user) instanceof \App\Models\User) {
                        // Add notification
                        Notification::create([
                            'user_id' => $shop_user->id,
                            'message' => 'Order masuk di toko ' . $invoice->shop->name
                                . ' dengan nomor order ' . $invoice->getInvoiceNumber()
                                . '. Mohon segera diproses.',
                            'priority' => 3,
                            'meta' => [
                                'url' => route('member.customerorder.show', $invoice->id),
                                'label' => __('Order Detail')
                            ]
                        ]);
                    }
                }
            }

            if ($memberId > 0) {
                balance_sync($memberId, true); // force update balance
            }
        }

        return view('member.orderflow.thankyou', compact('cartTotal', 'member', 'showFooter', 'invoice'));
    }

    /**
     * Set notes to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse.
     */
    protected function setNotes(Request $request)
    {
        $success = false;
        $message = __('Failed to set notes');
        if ($request->has('notes')) {
            $cookie_cart = Cookie::get('cart') ?? '[]';
            $cookie = (is_array($cookie_cart)) ? $cookie_cart : json_decode($cookie_cart, true);
            $cookie['notes'] = $request->input('notes');
            Cookie::queue('cart', json_encode($cookie), 60);
            $success = true;
            $message = __('Successfully set the note');
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }
}
