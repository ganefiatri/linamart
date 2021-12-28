<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\MailQueue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MailQueueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $query = MailQueue::query();

        if (null !== $request->input('q')) {
            $query->where('mail_class', 'like', '%'. $request->input('q') .'%');
            $query->orWhere('mail_to', 'like', '%'. $request->input('q') .'%');
        }

        $items = $query->orderBy('created_at', 'desc')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        $showFooter = true;
        
        return view('super.mailqueue.index', compact('items', 'showFooter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MailQueue  $mailqueue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, MailQueue $mailqueue)
    {
        if ($request->has('force')) {
            if (is_array($params = $mailqueue->mail_params)) {
                if (array_key_exists('model', $params) && array_key_exists('id', $params)) {
                    $model = new $params['model'];
                    $model = $model->find($params['id']);
                    if ($model instanceof $params['model']) {
                        $emailClass = new $mailqueue->mail_class($model);
                        if ($emailClass instanceof $mailqueue->mail_class) {
                            \Illuminate\Support\Facades\Mail::to($mailqueue->mail_to)->send($emailClass);
                            $mailqueue->executed = 1;
                            $mailqueue->executed_at = date('Y-m-d H:i:s');
                        }
                    }
                }
            }
        } else {
            $mailqueue->executed = 0;
        }
        $mailqueue->save();
        
        return redirect()->back()->with('update', 'Data Anda telah berhasil dieksekusi ulang.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MailQueue  $mailqueue
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(MailQueue $mailqueue)
    {
        $message = __('Your data is successfully deleted');
        $mailqueue->delete();

        return redirect()->back()->with('delete', $message);
    }

    /**
     * Clear old data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear(Request $request)
    {
        $request->validate(
            [
                'email' => 'nullable|email',
            ],
            [
                'email.email' => 'Format email salah. ex: nama@email.com.',
            ]
        );

        $message = __('No data has been successfully deleted');
        if ($request->has('range') || $request->has('mail_to')) {
            $query = MailQueue::query();
            $query->where('executed', 1);
            if (!empty($range = $request->input('range'))) {
                $date = Carbon::now();
                switch ($range) {
                    case 'last_day':
                        $date->subDay();
                        break;
                    case 'last_week':
                        $date->subWeek();
                        break;
                    case 'last_month':
                        $date->subMonth();
                        break;
                    case 'last_year':
                        $date->subYear();
                        break;
                    case 'last_2_year':
                        $date->subYears(2);
                        break;
                    case 'last_3_year':
                        $date->subYears(3);
                        break;
                }
                $query->whereDate('executed_at', '<=', $date);
            }

            if (!empty($mail_to = $request->input('mail_to'))) {
                $query->where('mail_to', $mail_to);
            }

            if (($count = $query->count()) > 0) {
                $delete = $query->delete();
                if ($delete) {
                    $message = $count . ' data has been successfully deleted.';
                }
            }
        }

        return redirect()->back()->with('delete', $message);
    }
}
