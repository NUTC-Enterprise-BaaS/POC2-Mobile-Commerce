<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use PushNotification;

class StoreSendPoints extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $notification;
    protected $deviceToken;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification, $deviceToken)
    {
        $this->notification = $notification;
        $this->deviceToken = $deviceToken;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $deviceTokens = $this->deviceToken;
        foreach ($deviceTokens as $key => $deviceToken) {
            switch ($this->notification['type']) {
                case 'storesend':
                    if ($deviceToken['device'] == 0) {
                        $deviceGcm = PushNotification::DeviceCollection([
                            PushNotification::Device($deviceToken['device_token']),
                        ]);
                        $dataJson = json_encode($this->notification, JSON_UNESCAPED_UNICODE);
                        $push = PushNotification::app('appNameAndroid');
                        $push->adapter->setAdapterParameters(['sslverifypeer' => false]);
                        $push->to($deviceGcm)->send($dataJson);
                    }
                    if ($deviceToken['device'] == 1) {
                        $devices = PushNotification::DeviceCollection([
                            PushNotification::Device($deviceToken['device_token']),
                        ]);
                        $message = PushNotification::Message($this->notification['shop_name']. ' 店家 點數消費通知', [
                            'custom' => [$this->notification]
                        ]);
                        PushNotification::app('appNameIOS')
                            ->to($devices)
                            ->send($message);
                        sleep(0.5);
                    }
                    break;
                }
            }
    }
}
