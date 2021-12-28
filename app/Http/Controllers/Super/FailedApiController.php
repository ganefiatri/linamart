<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\ApiRequest;
use Illuminate\Http\Request;

class FailedApiController extends Controller
{
    /**
     * Display all failed api request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    protected function index(Request $request)
    {
        $items = ApiRequest::where('success', 0)
            ->orderBy('failed_at', 'desc')
            ->paginate(10);

        if (null !== $request->input('q')) {
            $items = ApiRequest::where('success', 0)
                ->where('type', 'like', '%'. $request->input('q') .'%')
                ->orderBy('failed_at', 'desc')
                ->paginate(10);
        }

        $showFooter = false;
        
        return view('super.failedapi.index', compact('items', 'showFooter'));
    }

    /**
     * Re execution / repeat by manual execution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ApiRequest  $apiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reExecute(Request $request, ApiRequest $apiRequest)
    {
        $meta = $apiRequest->meta ?? [];
        switch ($apiRequest->type) {
            case 'balance_info_api':
                $member_id = $meta['params']['member_id'] ?? 0;
                if ($member_id > 0) {
                    balance_info_api($member_id);
                }
                break;
            case 'balance_transfer':
                $member_from_id = $meta['params']['member_from_id'] ?? 0;
                $member_to_id = $meta['params']['member_to_id'] ?? 0;
                $nominal = $meta['params']['nominal'] ?? 0;
                $fee = $meta['params']['fee'] ?? 0;
                if ($member_from_id > 0 && $member_to_id > 0 && $nominal > 0) {
                    balance_transfer($member_from_id, $member_to_id, $nominal, $fee);
                }
                break;
        }

        $apiRequest->delete();

        return redirect()->back()->with('update', 'Data Anda telah berhasil dieksekusi ulang.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApiRequest $apiRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ApiRequest $apiRequest)
    {
        $message = __('Your data is successfully deleted');
        $apiRequest->delete();

        return redirect()->back()->with('delete', $message);
    }
}
