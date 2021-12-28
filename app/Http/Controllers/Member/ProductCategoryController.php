<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        if (config('global.allow_add_category') <= 0) {
            abort(401);
        }
    }

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

        $items = ProductCategory::whereHas('shop', function ($q) use ($member) {
            $q->where('member_id', $member->id);
        })->paginate(10);

        if (null !== $request->input('q')) {
            $items = ProductCategory::where('title', 'like', '%' . $request->input('q') . '%')
                ->whereHas('shop', function ($q) use ($member) {
                    $q->where('member_id', $member->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $items = ProductCategory::whereHas('shop', function ($q) use ($member) {
                $q->where('member_id', $member->id);
            })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        $showFooter = true;

        return view('member.product-category.index', compact('items', 'showFooter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $shop = $this->getShop();
        if ($shop === null) {
            abort(401);
        }

        return view('member.product-category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $shop = $this->getShop();
        if ($shop === null) {
            abort(401);
        }

        $request->validate([
            'title' => [
                'required',
                function ($attribute, $value, $fail) {
                    $alpha_num_space = preg_match('/^[a-zA-Z0-9\s]+$/', $value);
                    if ($alpha_num_space == 0) {
                        $fail(ucfirst($attribute) . ' hanya diperbolehkan karakter huruf, angka dan spasi.');
                    }
                }
            ],
        ]);

        $create_data = $request->all();
        // create slug
        $create_data['slug'] = Str::slug($create_data['title']);
        $checkSlug = ProductCategory::where('slug', $create_data['slug'])->first();
        if ($checkSlug !== null) {
            $create_data['slug'] = $create_data['slug'] . '-' . time();
        }
        $create_data['shop_id'] = $shop->id;
        ProductCategory::create($create_data);

        return redirect()->back()->with('create', 'Data Anda telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(ProductCategory $productCategory)
    {
        return view('member.product-category.show', compact('productCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(ProductCategory $productCategory)
    {
        $shop = $this->getShop();
        if ($shop === null) {
            abort(401);
        }

        return view('member.product-category.edit', compact('productCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'title' => [
                'required',
                function ($attribute, $value, $fail) {
                    $alpha_num_space = preg_match('/^[a-zA-Z0-9\s]+$/', $value);
                    if ($alpha_num_space == 0) {
                        $fail(ucfirst($attribute) . ' hanya diperbolehkan karakter huruf, angka dan spasi.');
                    }
                }
            ],
            'slug' => [
                'required', 'unique:product_categories,slug,' . $productCategory->id,
                function ($attribute, $value, $fail) {
                    $check = preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
                    if ($check == 0) {
                        $fail('Format :attribute salah. Pisahkan spasi dengan tanda "-".');
                    }
                }
            ],
            'file_name' => 'file|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $update_data = $request->all();

        $file = $request->file('file_name');
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $file_name = time() . '.' . $file->extension();

            $upload_path = 'categories/' . $productCategory->shop_id;
            $path = Storage::putFileAs(
                'public/' . $upload_path,
                $file,
                $file_name
            );

            if ($path == 'public/' . $upload_path . '/' . $file_name) {
                $update_data['meta'] = [
                    'path' => $upload_path,
                    'file_name' => $file_name,
                    'original_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
        }

        $update_data['updated_at'] = date('c');
        $update = $productCategory->update($update_data);
        $message = 'Data Anda telah ' . ($update ? 'berhasil' : 'gagal') . ' diubah.';

        return redirect()->back()->with('update', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProductCategory $productCategory)
    {
        $message = 'Data Anda telah berhasil dihapus.';
        if ($productCategory->products->count() == 0) {
            $productCategory->delete();
        } else {
            $message = 'Data Anda tidak dapat dihapus karena memiliki order. 
                Produk hanya di-nonaktifkan.';
        }

        return redirect()->back()->with('delete', $message);
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

    /**
     * Get shop Object
     *
     * @return \App\Models\Shop|null
     */
    private function getShop()
    {
        $member = $this->getMember();
        if ($member !== null) {
            if ($member->shop !== null) {
                return $member->shop;
            }
        }

        return null;
    }
}
