<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Lookup;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
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

        $items = Product::whereHas('shop', function ($q) use ($member) {
            $q->where('member_id', $member->id);
        })->paginate(10);

        if (null !== $request->input('q')) {
            $items = Product::where('title', 'like', '%' . $request->input('q') . '%')
                ->whereHas('shop', function ($q) use ($member) {
                    $q->where('member_id', $member->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $items = Product::whereHas('shop', function ($q) use ($member) {
                $q->where('member_id', $member->id);
            })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        $statusList = Lookup::where('type', 'ProductStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        $showFooter = true;

        return view('member.product.index', compact('items', 'statusList', 'showFooter'));
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

        $status_list = Lookup::where('type', 'ProductStatus')
            ->orderBy('position')
            ->pluck('name', 'code')
            ->toArray();

        $category_list = ProductCategory::where('shop_id', $shop->id)
            ->orWhere('shop_id', 0)
            ->orderBy('title', 'asc')
            ->pluck('title', 'id')->all();

        $showFooter = true;

        return view('member.product.create', compact('status_list', 'category_list', 'showFooter'));
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
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'discount' => 'nullable|numeric|lt:price',
            'weight' => 'nullable|numeric',
        ]);

        $create_data = $request->all();
        // create slug
        $create_data['slug'] = Str::slug($create_data['title']);
        $checkSlug = Product::where('slug', $create_data['slug'])->first();
        if ($checkSlug !== null) {
            $create_data['slug'] = $create_data['slug'] . '-' . time();
        }
        $create_data['shop_id'] = $shop->id;
        $create_data['enabled'] = (isset($create_data['enabled'])) ? 1 : 0;
        $create_data['hidden'] = (isset($create_data['hidden'])) ? 1 : 0;
        Product::create($create_data);

        return redirect()->back()->with('create', 'Data Anda telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Product $product)
    {
        $status_list = Lookup::where('type', 'ProductStatus')
            ->orderBy('position')
            ->pluck('name', 'code')
            ->toArray();

        $showFooter = true;

        return view('member.product.show', compact('product', 'status_list', 'showFooter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Product $product)
    {
        $shop = $this->getShop();
        if ($shop === null) {
            abort(401);
        }

        $status_list = Lookup::where('type', 'ProductStatus')
            ->orderBy('position')
            ->pluck('name', 'code')
            ->toArray();

        $category_list = ProductCategory::where('shop_id', $shop->id)
            ->orWhere('shop_id', 0)
            ->orderBy('title', 'asc')
            ->pluck('title', 'id')->all();

        $showFooter = true;

        return view('member.product.edit', compact('product', 'status_list', 'category_list', 'showFooter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
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
                'required', 'unique:products,slug,' . $product->id,
                function ($attribute, $value, $fail) {
                    $check = preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value);
                    if ($check == 0) {
                        $fail('Format :attribute salah. Pisahkan spasi dengan tanda "-".');
                    }
                }
            ],
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'discount' => 'nullable|numeric|lt:price',
            'weight' => 'nullable|numeric',
        ]);

        $update_data = $request->all();
        $update_data['enabled'] = (isset($update_data['enabled'])) ? 1 : 0;
        $update_data['hidden'] = (isset($update_data['hidden'])) ? 1 : 0;

        $update_data['updated_at'] = date('c');
        $update = $product->update($update_data);

        return redirect()->back()->with('update', 'Data Anda telah berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $message = 'Data Anda telah berhasil dihapus.';
        if ($product->orders->count() == 0) {
            $product->delete();
        } else {
            $product->update(['active' => 0, 'enabled' => 0, 'hidden' => 1]);
            $message = 'Data Anda tidak dapat dihapus karena memiliki order. 
                Produk hanya di-nonaktifkan.';
        }

        return redirect()->back()->with('delete', $message);
    }

    /**
     * Upload image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadImage(Request $request, Product $product)
    {
        $request->validate([
            'file_name' => 'required|file|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        $file = $request->file('file_name');
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            // symlink first if still empty image
            $product_images_count = ProductImage::count();
            if ($product_images_count <= 0) {
                try {
                    symlink(storage_path('app/public'), public_path('storage'));
                } catch (Exception $e) {
                    Log::warning($e->getMessage());
                }
            }

            $meta = [
                'originalName' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'mimeType' => $file->getMimeType(),
            ];

            $file_name = time() . '.' . $file->extension();

            $upload_path = 'products/' . $product->shop_id;
            $path = Storage::putFileAs(
                'public/' . $upload_path,
                $file,
                $file_name
            );

            if ($path == 'public/' . $upload_path . '/' . $file_name) {
                $is_default = $request->has('is_default') ? 1 : 0;
                if ($is_default > 0) {
                    $product->images()->update(['is_default' => 0]);
                }
                
                $product->images()->create([
                    'path' => $upload_path,
                    'file_name' => $file_name,
                    'is_default' => $is_default,
                    'meta' => $meta
                ]);
            }
        }

        return redirect()->back()->with('create', 'Image baru telah berhasil diupload.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductImage  $productImage
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyImage(ProductImage $productImage)
    {
        $message = 'Data Anda telah berhasil dihapus.';
        if (file_exists($file = storage_path('app/public/' . $productImage->path . '/' . $productImage->file_name))) {
            unlink($file);
        }
        $productImage->delete();

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
