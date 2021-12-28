<?php

namespace App\Jobs;

use App\Models\MailQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use ReflectionClass;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * MailQueue object
     *
     * @var MailQueue
     */
    protected $mailQueue;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MailQueue $mailQueue)
    {
        $this->mailQueue = $mailQueue;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $queue = $this->mailQueue;
        if ($queue instanceof \App\Models\MailQueue) {
            $emailClass = new $this->mailQueue->mail_class;
            if (is_array($params = $this->mailQueue->mail_params)) {
                if (array_key_exists('model', $params) && array_key_exists('id', $params)) {
                    $model = new $params['model'];
                    $model = $model->find($params['id']);
                    if ($model instanceof $params['model']) {
                        $emailClass = new $queue->mail_class($model);
                        if ($emailClass instanceof $queue->mail_class) {
                            \Illuminate\Support\Facades\Mail::to($queue->mail_to)->send($emailClass);
                        }
                    }
                }
            }
        }
    }
}
