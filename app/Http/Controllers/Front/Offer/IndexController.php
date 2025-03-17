<?php

namespace App\Http\Controllers\Front\Offer;

use App\Helpers\EmailTemplatesJsonParser\WebTemplateParser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\EmailTemplate;
use Carbon\Carbon;

//CMS
use App\Models\Offer;
use App\Models\Property;
use App\Models\RodoRules;
use App\Models\RodoSettings;
use App\Models\User;
use App\Repositories\Client\ClientRepository;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private string $pageTemplateView = 'admin.web-generator.index';

    private ClientRepository $clientRepository;
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
    public function show(Offer $offer)
    {

        if ($this->isOfferExpired($offer)) {
            return view('front.offer.not-found');
        }

        if ($offer->status == 2) {
            throw new NotFoundHttpException(); // Throws a 404 exception
        }

        if ($offer->status != 3) {
            $offer->status = 3;
            $offer->readed_at = Carbon::now();
            $offer->save();
        }

        if ($offer->properties) {
            $propertyIds = json_decode($offer->properties);
            if ($propertyIds) {
                $selectedOffer = Property::whereIn('id', $propertyIds)->get();
            } else {
                $selectedOffer = collect();
            }
        } else {
            $selectedOffer = collect();
        }
        $entry = Client::make();

        $required_rodo_rules = $this->getRequiredRodoRules();
        $obligation  = RodoSettings::find(1);






        //    check if offer has template
        if (!$offer->template_id) {
            return view('front.offer.show', compact('offer', 'selectedOffer', 'entry', 'required_rodo_rules', 'obligation'));
        }

        $template = EmailTemplate::find($offer->template_id);

        $templateParser = new WebTemplateParser($template->content);
        $templateParser->prepareBlocks();
        $html = $templateParser->render();
        $html = $this->replaceTitlePlaceholder($html, $offer);
        $html = $this->replaceTextPlaceholder($html, $offer);
        $html = $this->replaceOfferPlaceholder($html, $selectedOffer);
        $html = $this->replaceOfferAttachmentsPlaceholder($html, $offer);
        $html = $this->replaceUserFooterPlaceholder($html, $offer);
        return view($this->pageTemplateView, ['html' => $html, 'entry' => $entry, 'offer' => $offer, 'required_rodo_rules' => $required_rodo_rules, 'obligation' => $obligation]);
        // return view('front.offer.show', compact('offer', 'selectedOffer', 'html'));
    }

    private function replaceTitlePlaceholder(string $html, Offer $offer)
    {
        if ($offer->title) {
            return str_replace('%OFFER_TITLE_PLACEHOLDER%', $offer->title, $html);
        } else {
            return str_replace('%OFFER_TITLE_PLACEHOLDER%', '', $html);
        }
    }
    private function replaceTextPlaceholder(string $html, Offer $offer)
    {
        if ($offer->message) {
            return str_replace('%OFFER_TEXT_PLACEHOLDER%', $offer->message, $html);
        } else {
            return str_replace('%OFFER_TEXT_PLACEHOLDER%', '', $html);
        }
    }
    private function replaceOfferPlaceholder(string $html, $offerSelectedProperties)
    {
        if ($offerSelectedProperties) {
            $html = str_replace('%OFFER_PLACEHOLDER%', view('front.offer.property_list', ['properties' => $offerSelectedProperties])->render(), $html);
        } else {
            $html = str_replace('%OFFER_PLACEHOLDER%', '', $html);
        }
        return $html;
    }

    private function replaceOfferAttachmentsPlaceholder(string $html, Offer $offer)
    {
        if ($offer->attachments) {
            $html = str_replace('%OFFER_ATTACHMENTS_PLACEHOLDER%', view('front.offer.attachments', ['attachments' => json_decode($offer->attachments)])->render(), $html);
        } else {
            $html = str_replace('%OFFER_ATTACHMENTS_PLACEHOLDER%', '', $html);
        }
        return $html;
    }

    private function replaceUserFooterPlaceholder(string $html, Offer $offer)
    {
        $user = User::find($offer->user_id);
        if ($user && $user->signature) {
            $html = str_replace('%USER_FOOTER_PLACEHOLDER%', $user->signature, $html);
        } else {
            $html = str_replace('%USER_FOOTER_PLACEHOLDER%', '', $html);
        }
        return $html;
    }

    private function getRequiredRodoRules()
    {
        $rules = RodoRules::whereIn('id', [1, 2, 3])->get();
        return $rules;
    }

    private function isOfferExpired(Offer $offer)
    {
        if (!$offer->date_end) {
            return false;
        }
        $endDate = Carbon::parse($offer->date_end)->format('Y-m-d');
        if (Carbon::parse($endDate)->isPast()) {
            return true;
        }
        return false;
    }

    public function store(Offer $offer, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'rule1' => ['nullable'],
            'rule2' => ['nullable'],
            'rule3' => ['nullable'],
        ]);

        $toInsert = $validated;
        $client = $this->clientRepository->createClient($toInsert);
        $this->setOfferClient($offer, $client);

        return redirect()->back()->with('success', 'Dziękujemy za rejestrację!');
    }

    private function setOfferClient(Offer $offer, Client $client)
    {
        $offer->client_id = $client->id;
        $offer->is_new_client = 0;
        $offer->save();
    }
}
