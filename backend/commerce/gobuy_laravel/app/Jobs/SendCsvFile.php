<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCsvFile extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $email;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $userEmail = $this->email['email'];
        $userName = $this->email['name'];
        $csvPath = $this->email['path'];
        $mailer->send('emails.user', ['user' => $userName], function($message) use ($userEmail,
            $csvPath) {
            $message->to($userEmail)->subject('使用紀錄');
            $message->attach($csvPath);
        });
    }
}
