<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactFormRequest;
use App\Mail\ChatSend;
use App\Models\Building;
use App\Models\Floor;
use App\Models\Investment;
use App\Models\Property;
use App\Models\RodoRules;
use App\Models\RodoSettings;
use App\Notifications\PropertyNotification;
use App\Repositories\Client\ClientRepository;
use App\Repositories\InvestmentRepository;
use App\Services\IframeSettingsService;
use App\Services\Strategies\HousesStrategy;
use App\Services\Strategies\MultiBuildingStrategy;
use App\Services\Strategies\SingleBuildingStrategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;

class IframePageController extends Controller
{
    private $strategy;
    private $repository;
    private $clientRepository;
    private $iframeSettingsService;

    public function __construct(
        InvestmentRepository $investmentRepository,
        ClientRepository $clientRepository,
        IframeSettingsService $iframeSettingsService
    ) {
        $this->repository = $investmentRepository;
        $this->clientRepository = $clientRepository;
        $this->iframeSettingsService = $iframeSettingsService;
        $this->strategy = null;
    }

    public function index(Request $request, Investment $investment, Property $property)
    {
        switch ($investment->type) {
            case 2:
                $this->strategy = new SingleBuildingStrategy($request, $investment);
                break;
            case 3:
                $this->strategy = new HousesStrategy($request, $investment);
                break;
            default:
                abort(404);
        }

        $properties = $this->strategy->handle();

        if (empty($properties)) {
            abort(404);
        }

        $uniqueRooms = $this->repository->getUniqueRooms($properties);
        $iframeSettings = $this->iframeSettingsService->getSettings($investment);

        return view('front.iframe.index', compact('investment', 'properties', 'uniqueRooms', 'iframeSettings'));
    }

    public function single(Request $request, Investment $investment, Property $property)
    {
        $property->timestamps = false;
        $property->increment('views');

        $custom_css = $investment->iframe_css;
        $rules = RodoRules::orderBy('sort')->whereActive(1)->get();
        $obligation = RodoSettings::find(1);
        $iframeSettings = $this->iframeSettingsService->getSettings($investment);
        return view('front.iframe.single', compact('investment', 'property', 'custom_css', 'rules', 'obligation', 'iframeSettings'));
    }

    public function apartmentBuilding(Request $request, Investment $investment, Floor $floor, Property $property)
    {
        $property->timestamps = false;
        $property->increment('views');

        $custom_css = $investment->iframe_css;
        $rules = RodoRules::orderBy('sort')->whereActive(1)->get();
        $obligation = RodoSettings::find(1);
        $iframeSettings = $this->iframeSettingsService->getSettings($investment);

        return view('front.iframe.single', compact('investment', 'property', 'floor', 'custom_css', 'rules', 'obligation', 'iframeSettings'));

    }

    public function apartmentMultiBuilding(Request $request, Investment $investment, Building $building, Floor $floor, Property $property)
    {

        $property->timestamps = false;
        $property->increment('views');

        $custom_css = $investment->iframe_css;
        $rules = RodoRules::orderBy('sort')->whereActive(1)->get();
        $obligation = RodoSettings::find(1);
        $iframeSettings = $this->iframeSettingsService->getSettings($investment);

        return view('front.iframe.single', compact('investment', 'property', 'floor', 'custom_css', 'rules', 'obligation', 'iframeSettings'));
    }

    public function contact(ContactFormRequest $request, $id)
    {
        try {
            $property = Property::find($id);

            $client = $this->clientRepository->createClient($request, $property);
            $property->notify(new PropertyNotification($request, $property));
            Mail::to(settings()->get("page_email"))->send(new ChatSend($request, $client, $property));

            if (count(Mail::failures()) == 0) {
                $cookie_name = 'dp_';
                foreach ($_COOKIE as $name => $value) {
                    if (stripos($name, $cookie_name) === 0) {
                        Cookie::queue(
                            Cookie::forget($name)
                        );
                    }
                }
            }
        } catch (\Throwable $exception) {
        }
        return response()->json(['status' => 'success', 'message' => "Wiadomość została wysłana. Dziękujemy za kontakt!"], 201);
    }

    public function token(Request $request)
    {

        return response()->json(['token' => csrf_token()]);
    }

    public function withContactForm(Request $request, Investment $investment, Property $property)
    {
        switch ($investment->type) {
            case 1:
                $this->strategy = new MultiBuildingStrategy($request, $investment);
                break;
            case 2:
                $this->strategy = new SingleBuildingStrategy($request, $investment);
                break;
            case 3:
                $this->strategy = new HousesStrategy($request, $investment);
                break;
            default:
                abort(404);
        }

        $properties = $this->strategy->handle();

        if (empty($properties)) {
            abort(404);
        }

        $uniqueRooms = $this->repository->getUniqueRooms($properties);
        $iframeSettings = $this->iframeSettingsService->getSettings($investment);

        return view('front.iframe.external-contact-form', compact('investment', 'properties', 'uniqueRooms', 'iframeSettings'));
    }

    public function withContactFormCreate(ContactFormRequest $request, Investment $investment)
    {
        try {
            $validated = $request->validate([
                'investment_id' => 'required|exists:investments,id',
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'nullable|string',
            ]);

            $investmentName = Investment::find($request->investment_id)->name;

            $client = $this->clientRepository->createClient(
                [
                    'name' => $request->name,
                    'lastname' => $request->surname,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'message' => $request->message,
                    'ip' => $request->ip(),
                    'investment_id' => $request->investment_id,
                    'investment_name' => $investmentName,
                ],null, 1, 'iframe'
            );
        } catch (\Throwable $exception) {
        }
        return response()->json(['status' => 'success', 'message' => "Wiadomość została wysłana. Dziękujemy za kontakt!"], 201);
    }
}
