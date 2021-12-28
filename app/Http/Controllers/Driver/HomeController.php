<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\OrderProcess;
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
     * Show the application home.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('driver.home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $driver = $this->getDriver();
        $stats = [
            'total_delivery' => OrderProcess::where('driver_id', $driver->id)
                ->groupBy('invoice_id')->count(),
            'total_delivered' => OrderProcess::where(['driver_id' => $driver->id, 'status' => 3])
                ->groupBy('invoice_id')->count()
        ];

        $invoices = Invoice::whereHas('orderProcesses', function ($q) use ($driver) {
            $q->where('status', 2);
            $q->where('driver_id', $driver->id);
        })
            ->whereDoesntHave('orderProcesses', function ($q) {
                $q->where('status', '>', 2);
                $q->orWhere('status', '<', 0);
            })
            ->orderBy('id', 'desc')->paginate(10);

        return view('driver.dashboard', compact('stats', 'invoices'));
    }

    /**
     * Get driver model
     *
     * @return \App\Models\Driver
     */
    private function getDriver()
    {
        $driver = (!empty(Auth::user())) ? Auth::user()->driver : null;
        if ($driver === null) {
            abort(401);
        }

        return $driver;
    }
}
