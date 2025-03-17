<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;

//CMS
use App\Mail\ChatSend;
use App\Models\Page;
use App\Models\Property;
use App\Models\RodoRules;
use App\Notifications\PropertyNotification;
use App\Repositories\Client\ClientRepository;

class ContactController extends Controller
{

    private $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    function index()
    {
        $page = Page::where('id', 1)->first();

        return view('front.contact.index', [
            'rules' => RodoRules::orderBy('sort')->whereActive(1)->get(),
            'page' => $page
        ]);
    }

    function send(ContactFormRequest $request)
    {
        try {
            $client = $this->repository->createClient($request);

            $email = settings()->get("page_email");
            if (!is_array($email)) {
                $email = json_decode($email, true); // Decode JSON string if not already an array
            }

            if (is_array($email)) {
                $emailAddresses = [];
                foreach ($email as $entry) {
                    if (isset($entry['value'])) {
                        $emailAddresses[] = $entry['value'];
                    }
                }

                Log::channel('mail')->info('Sending email to: ' . implode(', ', $emailAddresses));

                Mail::to($emailAddresses)->send(new ChatSend($request, $client));

                Log::channel('mail')->info('Email sent successfully.');
            }
        } catch (\Throwable $exception) {
            Log::channel('mail')->error('Email sending failed: ' . $exception->getMessage());
        }

        return $request->has('back') && $request->get('back') == true
            ? redirect()->back()->with(
                'success',
                'Twoja wiadomość została wysłana. W najbliższym czasie skontaktujemy się z Państwem celem omówienia szczegółów!'
            )
            : redirect()->route('front.contact')->with(
                'success',
                'Twoja wiadomość została wysłana. W najbliższym czasie skontaktujemy się z Państwem celem omówienia szczegółów!'
            );
    }

    function property(ContactFormRequest $request, $id)
    {
        try {
            $property = Property::find($id);
            $client = $this->repository->createClient($request, $property);
            $property->notify(new PropertyNotification($request, $property));

            $email = settings()->get("page_email");
            if (!is_array($email)) {
                $email = json_decode($email, true); // Decode JSON string if not already an array
            }

            if (is_array($email)) {
                $emailAddresses = [];
                foreach ($email as $entry) {
                    if (isset($entry['value'])) {
                        $emailAddresses[] = $entry['value'];
                    }
                }

                Log::channel('mail')->info('Sending email to: ' . implode(', ', $emailAddresses));

                Mail::to($emailAddresses)->send(new ChatSend($request, $client, $property));

                Log::channel('mail')->info('Email sent successfully.');
            }
        } catch (\Throwable $exception) {
            Log::channel('mail')->error('Email sending failed: ' . $exception->getMessage());
        }

        return redirect()->back()->with(
            'success',
            'Twoja wiadomość została wysłana. W najbliższym czasie skontaktujemy się z Państwem celem omówienia szczegółów!'
        );
    }

    public function showToken() {
        echo csrf_token();
    }
}
