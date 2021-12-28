<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $options = Option::pluck('option_value', 'option_name')->toArray();

        return view('super.option.index', compact('options'));
    }

    /**
     * Update all aparams.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required',
            'tag_line' => 'required',
            'site_description' => 'required',
            'admin_email' => 'required|email',
            'visitor_tracking' => 'nullable|numeric',
            'invoice_due_days' => 'required|numeric',
            'invoice_auto_approval' => 'nullable|numeric',
            'admin_phone' => 'required',
            'admin_wa' => 'required',
        ]);

        $update_data = $request->all();
        unset($update_data['method']);
        unset($update_data['token']);
        foreach ($update_data as $field => $value) {
            $option = Option::where('option_name', $field)->first();
            if ($option instanceof \App\Models\Option) {
                $option->update(['option_value' => $value]);
            }
        }

        return redirect()->back()->with('update', 'Data Anda telah berhasil diubah.');
    }
}
