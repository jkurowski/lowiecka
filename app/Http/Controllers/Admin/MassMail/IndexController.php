<?php

namespace App\Http\Controllers\Admin\MassMail;

use App\Helpers\EmailScheduleStatuses;
use App\Helpers\EmailScheduleTypes;
use App\Helpers\EmailTemplatesJsonParser\EmailTemplateParser;
use App\Helpers\TemplateTypes;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientRules;
use App\Models\EmailSchedule;
use App\Models\EmailTemplate;
use App\Models\Investment;
use App\Models\RodoRules;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Services\EmailScheduleService;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index()
    {
        $users = Client::whereNotNull('mail')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'email' => $user->mail,
                'created_at' => $user->created_at,
                'city' => $user->city,
            ];
        });

        $templates = $this->getEmailTemplates([ TemplateTypes::NEWSLETTER, TemplateTypes::NOT_EDITABLE]);

        $investments = Investment::all()->pluck('name', 'id')->toArray();




        return view('admin.mass-mail.index', compact('users', 'templates', 'investments'));
    }

    private function getScheduleCounts()
    {
        $counts = EmailSchedule::all()->groupBy('status')->map(function ($group) {
            return $group->count();
        });
        return $counts;
    }

    public function schedule()
    {
        $scheduleEntries = EmailSchedule::all();
        $scheduleEntries = $scheduleEntries->map(function (EmailSchedule $entry) {
            $entry->status = EmailScheduleStatuses::getStatus($entry->status);
            unset($entry->content);
            return $entry;
        });
        $scheduleCounts = $this->getScheduleCounts();

        return view('admin.mass-mail.schedule', compact('scheduleEntries', 'scheduleCounts'));
    }
    public function destroySchedule(Request $request, $id)
    {
        $schedule = EmailSchedule::find($id);
        $schedule->delete();
        return response()->json(['success' => true, 'message' => 'Email został usunięty']);
    }

    private function getEmailTemplates(array $templateType)
    {

        return EmailTemplate::where('user_id', auth()->id())
            ->get()
            ->filter(function ($template) use ($templateType) {
                if ($template->meta) {
                    $meta = $template->meta;
                    if (isset($meta['template_type']) && in_array($meta['template_type'], $templateType)) {
                        return $template;
                    }
                }
            })
            ->pluck('name', 'id')
            ->toArray();
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            "users" => "required|array",
            "subject" => "required|string",
            "content" => "nullable|string",
            "template" => "nullable|exists:email_templates,id",
            "schedule-send" => "nullable|date",
            "test-email" => "nullable|email",
            "action" => "required|string",
        ], [
            'users.required' => 'Musisz wybrać co najmniej jednego użytkownika',
        ]);

        $emailScheduleService = app(EmailScheduleService::class);
        $emails = $this->getUsersEmails($validated['users']);
        $template = EmailTemplate::find($validated['template']);

        $htmlContent = $this->prepareEmailContent($template, $validated['content'], $validated['action']);

        $sendDate = $validated['schedule-send'] ?? now();
        $isImmediate = !$validated['schedule-send'];

        foreach ($emails as $email) {
            $client = Client::where('mail', $email)->first();
            $status = EmailScheduleStatuses::PENDING;
            $hasEmailConsent = $this->hasEmailConsent($client);
            if (!$hasEmailConsent) {
                $status = EmailScheduleStatuses::NO_CONSENT;
            }

            $schedule = $emailScheduleService->addToSchedule(
                EmailScheduleTypes::NEWSLETTER,
                $email,
                auth()->id(),
                $sendDate,
                (int)$validated['template'],
                $validated['subject'],
                $htmlContent,
                $validated['action'],
                $status
            );

            // Add tracking pixel to the content
            $trackingPixel = $this->generateTrackingPixel($schedule->id);
            $htmlContentWithTracking = $this->addTrackingPixelToContent($htmlContent, $trackingPixel);


            if ($isImmediate) {
                $this->sendImmediateEmail($email, $validated['subject'], $htmlContentWithTracking, $validated['action'], $schedule->id);
            }
        }

        $message = $isImmediate ? 'Emaile zostały wysłane' : 'Emaile zostały zaplanowane';

        return redirect()->back()->with('success', $message);
    }

    private function hasEmailConsent(string $email): bool
    {
        $client = Client::where('mail', $email)->first();
        if (!$client) {
            return false;
        }
        $rule = ClientRules::where('client_id', $client->id)->where('rule_id', 1)->first();
        return $rule ? true : false;
    }

    private function generateTrackingPixel(int $scheduleId): string
    {
        $trackingUrl = route('front.email.track', ['trackingId' => $scheduleId]);
        return '<img src="' . $trackingUrl . '" alt="" width="1" height="1" style="display:none;">';
    }

    private function addTrackingPixelToContent(string $content, string $trackingPixel): string
    {
        // Add the tracking pixel just before the closing </body> tag or after content if there is no </body> tag
        if (strpos($content, '</body>') !== false) {
            return str_replace('</body>', $trackingPixel . '</body>', $content);
        }

        return $content . $trackingPixel;
    }

    private function prepareEmailContent($template, $content, $action)
    {

        if ($template && $template->meta['template_type'] === TemplateTypes::NOT_EDITABLE) {
            return $template->content;
        }

        if ($action === 'text_template' || $action === 'upload_template') {
            return $content;
        } else {
            $templateParser = new EmailTemplateParser($template->content);
            $templateParser->prepareBlocks();
            return $templateParser->renderAsTableLayout();
        }
    }

    public function sendTest(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'action' => 'required|string',
            'subject' => 'required|string',
            'content' => 'string|nullable',
            'template' => 'nullable|exists:email_templates,id',
        ]);


        if ($validated['action'] === 'text_template') {
            $this->sendTextEmail($validated['subject'], $validated['content'], [$validated['email']]);
            return response()->json(['message' => 'Email został wysłany']);
        }

        if ($validated['action'] === 'create_template') {
            $template = EmailTemplate::find($validated['template']);

            $htmlContent = '';

            if ($template->meta['template_type'] === TemplateTypes::NOT_EDITABLE) {
                $htmlContent = $template->content;
            } else {
                $templateParser = new EmailTemplateParser($template->content);
                $templateParser->prepareBlocks();

                $htmlContent = $templateParser->renderAsTableLayout();
            }



            Mail::send('emails.dynamicTemplate', [
                'htmlContent' => $htmlContent,
            ], function ($message) use ($validated) {
                $message->to($validated['email'])->subject($validated['subject']);
            });
        }

        return response()->json(['message' => 'Email został wysłany']);
    }


    private function getUsersEmails(array $usersIds): array
    {
        return Client::whereIn('id', $usersIds)->pluck('mail')->toArray();
    }

    private function sendImmediateEmail($email, $subject, $content, $action, $scheduleId)
    {

        if(!$this->hasEmailConsent($email)) {
            return;
        }

        if ($action === 'text_template') {
            $this->sendTextEmail($subject, $content, [$email]);
        } else {
            Mail::send('emails.dynamicTemplate', ['htmlContent' => $content], function ($message) use ($subject, $email) {
                $message->to($email)->subject($subject);
            });
        }

        // Update the schedule status to sent
        EmailSchedule::where('id', $scheduleId)->update(['status' => EmailScheduleStatuses::SENT]);
    }

    private function sendTextEmail(string $subject, string $content, array $emails)
    {
        \Log::info('sendTextEmail', ['subject' => $subject, 'content' => $content, 'emails' => $emails]);

        Mail::send('emails.mass-mail', compact('subject', 'content'), function ($message) use ($subject, $emails) {
            $message->to($emails)->subject($subject);
        });
    }

    public function newsletterTemplates()
    {
  
        return view('admin.mass-mail.newsletter-templates', ['list' => $this->getNewsletterTemplates(), 'investment_id' => 0]);
    }

    private function getNewsletterTemplates()
    {
        // Get email templates with type newsletter
        return EmailTemplate::where('investment_id', 0)->orWhereNull('investment_id')
            ->get()
            ->filter(function ($template) {
                if ($template->meta) {
                    $meta = $template->meta;
                    if (isset($meta['template_type']) && ($meta['template_type'] === TemplateTypes::NEWSLETTER || $meta['template_type'] === TemplateTypes::NOT_EDITABLE)) {
                       
                        return $template;
                    }
                }
            });
    }
}
