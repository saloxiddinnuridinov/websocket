<?php

namespace App\Jobs;

use App\Http\Helpers\TransactionManage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\MainNotificationController;
use Illuminate\Support\Facades\Redis;

class RevokePassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $verify_code = rand(100000, 999999);

            Cache::store('database')->put($this->data['email'], [
                'name' => $this->data['name'],
                'surname' => $this->data['surname'],
                'verify_code' => $verify_code,
//                'password' => $this->data['password'],
            ], 600);

            $sms = new MainNotificationController();

            $message = [
                'title' => 'Skool',
                'name' => $this->data['name'],
                'verify_code' => $verify_code,
            ];

            echo "mail is starting to send \n";
            $sms->mail($this->data['email'], $message, 'revoke_password');
            echo "success \n";

        } catch (\Exception $exception) {
            $err = "File: " . $exception->getFile() . "%0D%0A" . 'Line: ' . $exception->getLine() . "%0D%0A" . 'Error: ' . $exception->getMessage();
            return $err;
        }
    }
}
