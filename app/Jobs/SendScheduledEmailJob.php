<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailSchedule;
use App\Traits\SMTPConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use function PHPUnit\Framework\isNull;

class SendScheduledEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SMTPConfig;



    /**
     * Create a new job instance.
     */
    public function __construct(public EmailSchedule $emailSchedule) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->setSettingsSMTPConfig();
            $this->logJobStart()
                ->sendEmail()
                ->logEmailSent();
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
    }

    public function logJobStart(): self
    {
        Log::info('logJobStart()', ['data' => $this->emailSchedule]);
        return $this;
    }

    public function sendEmail(): self
    {

        Log::info('IS static template', ['data' => $this->emailSchedule->action === 'text_template']);
        if ($this->emailSchedule->action === 'text_template') {

            
            // If template is not set - then it is email with static content - text email
            Mail::send('emails.mass-mail', ['subject' => $this->emailSchedule->subject, 'content' => $this->emailSchedule->content], function ($message) {
                $message->to($this->emailSchedule->email_address)
                    ->subject($this->emailSchedule->subject ?? 'No Subject');
            });
        } else {
            // If templates is set - then it is email with dynamic content
            Log::info('Sending email with dynamic content', ['data' => $this->emailSchedule]);

            Mail::send('emails.dynamicTemplate', ['htmlContent' => $this->emailSchedule->content], function ($message) {
                $message->to($this->emailSchedule->email_address)
                    ->subject($this->emailSchedule->subject ?? 'No Subject');
            });
        }
        return $this;
    }
    public function logEmailSent(): self
    {
        Log::info('logEmailSent()', ['data' => $this->emailSchedule]);
        return $this;
    }

    public function handleError(\Throwable $e): void
    {
        Log::error('Error sending email', [
            'data' => $this->emailSchedule,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        $this->fail($e);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Failed to send scheduled email", [
            'data' => $this->emailSchedule,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
