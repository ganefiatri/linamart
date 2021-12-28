<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (Auth::user() === null) {
            abort(401);
        }
        
        return redirect()->route(Auth::user()->role .'.home');
    }

    /**
     * List districts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse.
     */
    protected function districts(Request $request)
    {
        $district = new District();
        $districts = $district->getListDistricts();
        $collection = collect($districts);
        if ($request->has('q')) {
            $filtered = $collection->filter(function ($value, $key) use ($request) {
                return false !== stristr($value, $request->input('q'));
            });
            $districts = $filtered->all();
        }

        return response()->json($districts);
    }
}
