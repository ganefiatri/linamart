<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Driver;
use App\Models\Lookup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index(Request $request)
    {
        $items = Driver::orderBy('created_at', 'desc')->paginate(10);

        if (null !== $request->input('q')) {
            $items = Driver::where('name', 'like', '%'. $request->input('q') .'%')
                ->orWhere('email', 'like', '%'. $request->input('q') .'%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        $showFooter = true;
        
        return view('admin.driver.index', compact('items', 'showFooter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $district = new District();
        $districts = $district->getListDistricts();

        $genders = Lookup::where('type', 'Gender')
        ->orderBy('position')->pluck('name', 'code')
        ->toArray();

        $statusList = Lookup::where('type', 'ClientStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        return view('admin.driver.create', compact('districts', 'genders', 'statusList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'fields' => [
                'name' => 'required|min:4',
                'email' => 'required|email|unique:drivers,email',
                'phone' => 'required',
                'district_name' => 'required',
                'district_id' => 'required'
            ],
            'messages' => [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'name.min' => 'Nama minimal 4 karakter.',
                'email.required' => 'Email tidak boleh dikosongi.',
                'email.email' => 'Format email salah. ex: nama@email.com.',
                'phone.required' => 'No Telepon/WA tidak boleh dikosongi.',
                'district_name.required' => 'Kecamatan tidak boleh dikosongi.',
            ]
        ];

        if (!is_using_district()) {
            unset($rules['fields']['district_id']);
        }

        $request->validate($rules['fields'], $rules['messages']);

        $create_data = $request->all();
        if (empty($create_data['district_id'])) {
            $create_data['district_id'] = 0;
        }
        $driver = Driver::create($create_data);
        if ($driver instanceof \App\Models\Driver) {
            User::create(
                [
                    'name' => $driver->name,
                    'email' => $driver->email,
                    'password' => Hash::make('12345678'),
                    'role' => 'driver',
                    'driver_id' => $driver->id
                ]
            );
        }
 
        return redirect()->back()->with('create', 'Data Anda telah berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Driver $driver)
    {
        return view('admin.driver.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Driver $driver)
    {
        $district = new District();
        $districts = $district->getListDistricts();

        $genders = Lookup::where('type', 'Gender')
        ->orderBy('position')->pluck('name', 'code')
        ->toArray();

        $statusList = Lookup::where('type', 'ClientStatus')
            ->orderBy('position')->pluck('name', 'code')
            ->toArray();

        return view('admin.driver.edit', compact('driver', 'districts', 'genders', 'statusList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Driver $driver)
    {
        $rules = [
            'fields' => [
                'name' => 'required|min:4',
                'email' => 'required|email|unique:drivers,email,' . $driver->id,
                'phone' => 'required',
                'district_name' => 'required',
                'district_id' => 'required'
            ],
            'messages' => [
                'name.required' => 'Nama tidak boleh dikosongi.',
                'name.min' => 'Nama minimal 4 karakter.',
                'email.required' => 'Email tidak boleh dikosongi.',
                'email.email' => 'Format email salah. ex: nama@email.com.',
                'phone.required' => 'No Telepon/WA tidak boleh dikosongi.',
                'district_name.required' => 'Kecamatan tidak boleh dikosongi.',
            ]
        ];

        if (!is_using_district()) {
            unset($rules['fields']['district_id']);
        }
        
        $request->validate($rules['fields'], $rules['messages']);

        $update_data = $request->all();
        $update_data['updated_at'] = date('c');
        $driver->update($update_data);
 
        return redirect()->back()->with('update', 'Data Anda telah berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Driver $driver)
    {
        $message = __('Your data is successfully deleted');
        if ($driver->orderProcesses->count() == 0) {
            if ($driver->user !== null) {
                $driver->user()->delete();
            }
            $driver->delete();
        } else {
            $message = __('Unable to delete this data due to any relationship');
        }

        return redirect()->back()->with('delete', $message);
    }
}
