<?php

namespace App\Services;

use App\Helpers\EmailScheduleStatuses;
use App\Models\EmailSchedule;
use App\Helpers\EmailScheduleTypes;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendScheduledEmailJob;

class EmailScheduleService
{
    private EmailScheduleStatuses $emailScheduleStatuses;

    public function __construct(EmailScheduleStatuses $emailScheduleStatuses)
    {
        $this->emailScheduleStatuses = $emailScheduleStatuses;
    }

    public function addToSchedule(string $emailType, string $emailAddress, int $userId, string $scheduleDate, int $templateId, string $subject, string $content, string $action, string $status)
    {
        return EmailSchedule::create([
            'email_type' => $emailType,
            'email_address' => $emailAddress,
            'user_id' => $userId,
            'scheduled_date' => $scheduleDate,
            'template_id' => $templateId,
            'subject' => $subject,
            'content' => $content,
            'action' => $action,
            'status' => $status
        ]);
    }

    public function setStatus(int $id, string $status)
    {
        return EmailSchedule::findOrFail($id)->update(['status' => $status]);
    }

    public function getSchedule()
    {
        return EmailSchedule::with('user')->get();
    }

    public function sendScheduledEmails()
    {
        $scheduledEmails = $this->getScheduledEmails();

        $scheduledEmails->each(function (EmailSchedule $email) {
            try {
        
                $this->dispatchEmailJob($email);
                $this->setStatus($email->id, EmailScheduleStatuses::SENT);
            } catch (\Exception $e) {
                $this->handleEmailSendingError($email, $e);
            }
        });
    }

    private function getScheduledEmails()
    {
        return EmailSchedule::where('scheduled_date', '<=', now())->where('status', '!=', EmailScheduleStatuses::SENT)->get();
    }

    private function dispatchEmailJob(EmailSchedule $email)
    {
        \Log::info('dispatchEmailJob()', ['data' => $email]);
        SendScheduledEmailJob::dispatch($email);
    }

    private function handleEmailSendingError(EmailSchedule $email, \Exception $e)
    {
        \Log::error('Failed to send scheduled email', [
            'email' => $email,
            'error' => $e->getMessage()
        ]);
        $this->setStatus($email->id, EmailScheduleStatuses::FAILED);
    }
}
