<?php

namespace App\Console\Commands;

use App\Models\MailQueue;
use Illuminate\Console\Command;

class MailQueueSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailqueue:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute send mail on queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $queues = MailQueue::where('executed', 0)->orderBy('priority', 'desc')->limit(5)->get();
        $tot_executed = 0;
        if ($queues->count() > 0) {
            $queues->each(function ($queue) use ($tot_executed) {
                if (is_array($params = $queue->mail_params)) {
                    if (array_key_exists('model', $params) && array_key_exists('id', $params)) {
                        $model = new $params['model'];
                        $model = $model->find($params['id']);
                        if ($model instanceof $params['model']) {
                            $emailClass = new $queue->mail_class($model);
                            if ($emailClass instanceof $queue->mail_class) {
                                // only for demo
                                $mail_to = $queue->mail_to;
                                if (str_contains($mail_to, 'email.com')) {
                                    $mail_to = 'bangodell@gmail.com';
                                    $queue->mail_to = $mail_to;
                                }
                                \Illuminate\Support\Facades\Mail::to($mail_to)->send($emailClass);

                                $queue->executed = 1;
                                $queue->executed_at = date('Y-m-d H:i:s');
                                $queue->save();

                                $tot_executed++;
                            }
                        }
                    }
                }
            });
        }

        return $tot_executed;
    }
}
