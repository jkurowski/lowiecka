<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMassMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subject;
    protected $content;
    protected $emails;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $content, array $emails)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->emails = $emails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send('emails.mass-mail', ['subject' => $this->subject, 'content' => $this->content], function ($message) {
            $message->to($this->emails)->subject($this->subject);
        });
    }
}
