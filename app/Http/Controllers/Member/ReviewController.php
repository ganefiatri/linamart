<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index(Request $request)
    {
        $member = $this->getMember();
        if ($member === null) {
            abort(401);
        }

        $reviews = Review::where('status', 1)
            ->whereHas('order', function ($q) use ($member) {
                $q->where('member_id', $member->id);
            });
        $reviewIds = $reviews->pluck('order_id')->toArray();

        $completeds = $reviews->orderBy('created_at', 'desc')->paginate(10);

        $pendings = Order::select('id', 'product_id', 'created_at')
            ->with('product')
            ->where('member_id', $member->id)
            ->whereNotIn('id', $reviewIds)
            ->where('status', 4)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $shop = Shop::where('member_id', $member->id)->first();
        $myProductReviews = null;
        if ($shop instanceof \App\Models\Shop) {
            $myProductIds = Product::select('id')
                ->where('shop_id', $shop->id)
                ->pluck('id')
                ->toArray();

            $myProductReviews = Review::whereIn('product_id', $myProductIds)
                ->orderBy('id', 'desc')
                ->paginate(10);
        }

        $showFooter = true;

        return view('member.review.index', compact('pendings', 'completeds', 'myProductReviews', 'showFooter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request, Product $product)
    {
        $showFooter = true;
        $order = null;
        if ($request->has('order')) {
            $order = Order::findOrFail($request->input('order'));
            if ($order instanceof \App\Models\Order) {
                $exist = Review::where('order_id', $order->id)->exists();
                if ($exist) {
                    abort(401, 'Anda pernah mengulas produk ini');
                }
            }
        } else {
            abort(401, 'Tidak diperbolehkan mengulas produk ini');
        }

        return view('member.review.create', compact('product', 'order', 'showFooter'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Product $product)
    {
        $owner = null;
        $ownerUser = null;
        $user = Auth::user();
        if ($product instanceof \App\Models\Product) {
            if (($shop = $product->shop) instanceof \App\Models\Shop) {
                if (($owner = $shop->member) instanceof \App\Models\Member) {
                    if (($ownerUser = $owner->user) instanceof \App\Models\User) {
                        if (($user instanceof \App\Models\User) && ($ownerUser->id == $user->id)) {
                            abort(401); //if own product
                        }
                    }
                }
            }
        }

        $request->validate(
            [
                'rating' => 'required|numeric|min:1|max:5',
                'comment' => 'required'
            ],
            [
                'rating.required' => 'Kolong rating tidak boleh dikosongi.',
                'comment.required' => 'Kolom komentar tidak boleh dikosongi.'
            ]
        );

        $create_data = $request->all();
        $create_data['product_id'] = $product->id;
        $create_data['status'] = 1;
        $review = Review::create($create_data);
        if ($review instanceof \App\Models\Review) {
            // notice product owner
            if (($ownerUser instanceof \App\Models\User) && ($user instanceof \App\Models\User)) {
                Notification::create([
                    'user_id' => $ownerUser->id,
                    'message' => 'Product Anda (' . $product->title . ') 
                        mendapatkan rating ' . $request->input('rating') . ' dari ' . $user->name,
                    'priority' => 1,
                    'meta' => [
                        'url' => route('member.review.show', $review->id),
                        'label' => __('More Detail')
                    ]
                ]);
            }
        }

        return redirect()->back()->with('create', 'Data Anda telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Review $review)
    {
        $user = Auth::user();
        $product = $review->product;
        $is_owner = false;
        if ($product instanceof \App\Models\Product) {
            if (($shop = $product->shop) instanceof \App\Models\Shop) {
                if (($owner = $shop->member) instanceof \App\Models\Member) {
                    if (($ownerUser = $owner->user) instanceof \App\Models\User) {
                        if (($user instanceof \App\Models\User) && ($ownerUser->id == $user->id)) {
                            $is_owner = true;
                        }
                    }
                }
            }
        }

        return view('member.review.show', compact('review', 'is_owner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Review $review)
    {
        $update_data = $request->all();
        $update_data['updated_at'] = date('c');
        $update = $review->update($update_data);
        $message = 'Data Anda telah ' . ($update ? 'berhasil' : 'gagal') . ' diubah.';

        return redirect()->back()->with('update', $message);
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
