<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index(Request $request)
    {
        $items = ProductUnit::paginate(10);

        if (null !== $request->input('q')) {
            $items = ProductUnit::where('title', 'like', '%' . $request->input('q') . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $items = ProductUnit::orderBy('created_at', 'desc')
                ->paginate(10);
        }

        $showFooter = true;

        return view('admin.product-unit.index', compact('items', 'showFooter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('admin.product-unit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'title' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $alpha_num_space = preg_match('/^[a-zA-Z0-9\s]+$/', $value);
                        if ($alpha_num_space == 0) {
                            $fail(ucfirst($attribute) . ' hanya diperbolehkan karakter huruf, angka dan spasi.');
                        }
                    }
                ],
                'code' => 'required|alpha_dash|unique:product_units,code',
            ],
            [
                'title.required' => 'Nama unit tidak boleh dikosongi.',
                'code.required' => 'Kode unit tidak boleh dikosongi.',
                'code.alpha_dash' => 'Hanya diperbolehkan huruf, angka, - atau _.',
                'code.unique' => 'Kode ini sudah pernah dipakai, mohon gunakan kode lain.'
            ]
        );

        $create_data = $request->all();
        ProductUnit::create($create_data);

        return redirect()->back()->with('create', 'Data Anda telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductUnit  $productUnit
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(ProductUnit $productUnit)
    {
        return view('admin.product-unit.show', compact('productUnit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductUnit  $productUnit
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(ProductUnit $productUnit)
    {
        $isUsed = Product::where('unit', $productUnit->code)->exists();
        return view('admin.product-unit.edit', compact('productUnit', 'isUsed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductUnit  $productUnit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ProductUnit $productUnit)
    {
        $request->validate(
            [
                'title' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $alpha_num_space = preg_match('/^[a-zA-Z0-9\s]+$/', $value);
                        if ($alpha_num_space == 0) {
                            $fail(ucfirst($attribute) . ' hanya diperbolehkan karakter huruf, angka dan spasi.');
                        }
                    }
                ],
                'code' => 'required|alpha_dash|unique:product_units,code,' . $productUnit->id,
            ],
            [
                'title.required' => 'Nama unit tidak boleh dikosongi.',
                'code.required' => 'Kode unit tidak boleh dikosongi.',
                'code.alpha_dash' => 'Hanya diperbolehkan huruf, angka, - atau _.',
                'code.unique' => 'Kode ini sudah pernah dipakai, mohon gunakan kode lain.'
            ]
        );

        $update_data = $request->all();

        $update_data['updated_at'] = date('c');
        $oldCode = $productUnit->code;
        $newCode = $update_data['code'];
        $update = $productUnit->update($update_data);
        if ($update && ($oldCode != $newCode)) {
            $isUsed = Product::where('unit', $oldCode)->exists();
            if ($isUsed) {
                Product::where('unit', $oldCode)
                ->update(['unit' => $newCode]);
            }
        }
        $message = 'Data Anda telah ' . ($update ? 'berhasil' : 'gagal') . ' diubah.';

        return redirect()->back()->with('update', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductUnit  $productUnit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProductUnit $productUnit)
    {
        $message = 'Data Anda telah berhasil dihapus.';
        $used = Product::where('unit', $productUnit->code)->exists();
        if (!$used) {
            $productUnit->delete();
        } else {
            $message = 'Data Anda tidak dapat dihapus karena digunakan di beberapa produk.';
        }

        return redirect()->back()->with('delete', $message);
    }
}
