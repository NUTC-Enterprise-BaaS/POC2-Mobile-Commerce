<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use PushNotification;

class PushAppNotification extends Job implements ShouldQueue
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
                case 'promotions':
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
                        $message = PushNotification::Message($this->notification['shop_name'], [
                            'custom' => [$this->notification]
                        ]);
                        PushNotification::app('appNameIOS')
                            ->to($devices)
                            ->send($message);
                    }
                    break;
                case 'scratch':
                    $arrays[] = [
                        'type' => 'scratch',
                        'result' => 0,
                        'message' => ['立即刮，立即送，快來分享給好友...'],
                        'lucky_token' => $deviceToken['lucky_token']
                    ];
                    foreach ($arrays as $key => $array) {
                        if ($array['lucky_token'] == $deviceToken['lucky_token']) {
                            if ($deviceToken['device'] == 0) {
                                $deviceGcm = PushNotification::DeviceCollection([
                                PushNotification::Device($deviceToken['device_token']),
                                ]);
                            $dataJson = json_encode($array, JSON_UNESCAPED_UNICODE);
                            $push = PushNotification::app('appNameAndroid');
                            $push->adapter->setAdapterParameters(['sslverifypeer' => false]);
                            $push->to($deviceGcm)->send($dataJson);
                            }
                            if ($deviceToken['device'] == 1) {
                                $devices = PushNotification::DeviceCollection([
                                    PushNotification::Device($deviceToken['device_token']),
                                ]);
                                $message = PushNotification::Message('立即刮，立即送，快來分享給好友...', [
                                    'custom' => [$array]
                                ]);
                                PushNotification::app('appNameIOS')
                                    ->to($devices)
                                    ->send($message);
                            }
                        }
                    }
                    break;
                case 'payment':
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
                        $message = PushNotification::Message('智付寶Pay2go催繳通知', [
                            'custom' => [$this->notification]
                        ]);
                        PushNotification::app('appNameIOS')
                            ->to($devices)
                            ->send($message);
                    }
                    break;
                case 'transferPoint':
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
                        $sendMessage = '轉讓點數: ' . $this->notification['point'] . ' 給 ' . $this->notification['receive_phone'] . ' , ' . $this->notification['receive_email'] . ' 使用者';
                        $devices = PushNotification::DeviceCollection([
                            PushNotification::Device($deviceToken['device_token']),
                        ]);
                        $message = PushNotification::Message($sendMessage, [
                            'custom' => [$this->notification]
                        ]);
                        PushNotification::app('appNameIOS')
                            ->to($devices)
                            ->send($message);
                    }
                    break;
                case 'confirmPoint':
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
                        if ($this->notification['state'] == 1) {
                            $sendMessage = $this->notification['receive_email'] . ' 已收到點數: ' . $this->notification['points'];
                            $message = PushNotification::Message($sendMessage, [
                                'custom' => [$this->notification]
                            ]);
                            PushNotification::app('appNameIOS')
                                ->to($devices)
                                ->send($message);
                        }
                        else {
                            $sendMessage = $this->notification['receive_email'] . ' 已取消點數: ' . $this->notification['points'];
                            $message = PushNotification::Message($sendMessage, [
                                'custom' => [$this->notification]
                            ]);
                            PushNotification::app('appNameIOS')
                                ->to($devices)
                                ->send($message);
                        }
                    }
                    break;
                case 'special_activity':
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
                        $message = PushNotification::Message($this->notification['title'], [
                            'custom' => [$this->notification]
                        ]);
                        PushNotification::app('appNameIOS')
                            ->to($devices)
                            ->send($message);
                    }
                    break;
                case 'promotions_activity':
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
                        $message = PushNotification::Message($this->notification['title'], [
                            'custom' => [$this->notification]
                        ]);
                        PushNotification::app('appNameIOS')
                            ->to($devices)
                            ->send($message);
                    }
                    break;
                case 'shareScratch':
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
                        $message = PushNotification::Message('收取由好友分享的刮刮樂...', [
                            'custom' => [$this->notification]
                        ]);
                        PushNotification::app('appNameIOS')
                            ->to($devices)
                            ->send($message);
                    }
                    break;
            }
        }
    }
}
