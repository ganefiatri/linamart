<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $items = Shipping::orderBy('created_at', 'desc')->paginate(10);

        if (null !== $request->input('q')) {
            $items = Shipping::where('title', 'like', '%'. $request->input('q') .'%')
                ->orWhere('cost', 'like', '%'. $request->input('q') .'%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        $showFooter = true;
        
        return view('super.shipping.index', compact('items', 'showFooter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('super.shipping.create');
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
                'title' => 'required|min:4',
                'distance_from' => 'required|integer',
                'distance_to' => 'required|integer|gt:distance_from',
                'cost' => 'required|integer',
            ],
            [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'name.min' => 'Nama minimal 4 karakter.',
                'distance_from.required' => 'Jarak dari tidak boleh dikosongi.',
                'distance_to.required' => 'Jarak hingga tidak boleh dikosongi.',
                'distance_to.gt' => 'Jarak hingga harus lebih besar dari jarak dari.',
                'cost.required' => 'Biaya tidak boleh dikosongi.',
            ]
        );

        Shipping::create($request->all());
 
        return redirect()->back()->with('create', 'Data Anda telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Shipping $shipping)
    {
        return view('super.shipping.show', compact('shipping'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Shipping $shipping)
    {
        return view('super.shipping.edit', compact('shipping'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Shipping $shipping)
    {
        $request->validate(
            [
                'title' => 'required|min:4',
                'distance_from' => 'required|integer',
                'distance_to' => 'required|integer|gt:distance_from',
                'cost' => 'required|integer',
            ],
            [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'name.min' => 'Nama minimal 4 karakter.',
                'distance_from.required' => 'Jarak dari tidak boleh dikosongi.',
                'distance_to.required' => 'Jarak hingga tidak boleh dikosongi.',
                'distance_to.gt' => 'Jarak hingga harus lebih besar dari jarak dari.',
                'cost.required' => 'Biaya tidak boleh dikosongi.',
            ]
        );

        $shipping->update($request->all());
 
        return redirect()->back()->with('create', 'Data Anda telah berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Shipping  $shipping
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Shipping $shipping)
    {
        $message = __('Your data is successfully deleted');
        if ($shipping->orders->count() == 0) {
            $shipping->delete();
        } else {
            $shipping->update(['enabled' => 0]);
            $message = __('Unable to delete this data due to any relationship');
        }

        return redirect()->back()->with('delete', $message);
    }
}
