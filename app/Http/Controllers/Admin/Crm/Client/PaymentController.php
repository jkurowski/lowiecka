<?php

namespace App\Http\Controllers\Admin\Crm\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\PropertyPayment;
use App\Repositories\ArticleRepository;
use App\Repositories\PropertyPaymentRepository;
use App\Services\ArticleService;
use Carbon\Carbon;
use Illuminate\Http\Request;

// CMS
use App\Traits\PropertyLinkTrait;
use App\Models\Property;
use App\Models\Client;

class PaymentController extends Controller
{
    private PropertyPaymentRepository $repository;
    use PropertyLinkTrait;

    public function __construct(PropertyPaymentRepository $repository)
    {
        $this->repository = $repository;
    }

//    public function index(Client $client)
//    {
//        $c = Client::with([
//            'properties.payments' => function ($query) {
//                $query->latest()->first();
//            },
//            'properties.relatedProperties'])->findOrFail($client->id);
//
//        foreach ($c->properties as $property) {
//            $property->latestPayment = $property->nextPaymentAfterToday();
//            $property->relatedPropertiesList = $property->relatedProperties;
//        }
//
//        return view('admin.crm.client.payments.index', [
//            'client' => $c
//        ]);
//    }

    public function index(Client $client)
    {
        $c = Client::with([
            'properties.payments', // Eager load all payments for each property
            'properties.relatedProperties' // Eager load related properties
        ])->findOrFail($client->id);

        foreach ($c->properties as $property) {

            $property->latestPayment = $property->payments
                ->where('due_date', '>', Carbon::today())
                ->sortBy('due_date')
                ->first();

            if (!$property->latestPayment) {
                $property->latestPayment = $property->payments
                    ->where('status', 0)
                    ->first();
                $property->late = 1;
            }

            $property->relatedPropertiesList = $property->relatedProperties;
        }

        return view('admin.crm.client.payments.index', [
            'client' => $c
        ]);
    }

    public function create(Property $property)
    {
        return view('admin.crm.client.payments.modal', ['property_id' => $property->id])->with('payment', PropertyPayment::make());
    }

    public function store(PaymentRequest $request)
    {
        $validatedData = $request->validated();

        if (request()->ajax()) {
            $this->repository->create($validatedData);
            return response()->json(['success' => true]);
        }

        return response('This endpoint only supports AJAX requests.', 400);
    }

    public function show(Client $client, Property $property)
    {
        $latestPayment = null;
        if ($property) {
            $latestPayment = $property->payments()->first();
        }

        return view('admin.crm.client.payments.show', [
            'client' => $client,
            'property' => $property,
            'property_url' => $this->getLinkToProperty($property),
            'backButton' => route('admin.crm.clients.payments', $client),
            'latestPayment' => $latestPayment
        ]);
    }

    public function generatePayments(Client $client, Property $property)
    {
        $investmentPayments = $property->investmentPayments;
        $propertyPrice = $property->price_brutto;

        foreach ($investmentPayments as $investmentPayment) {
            $amount = ($investmentPayment->amount / 100) * $propertyPrice;

            PropertyPayment::create([
                'property_id' => $property->id,
                'percent' => $investmentPayment->amount,
                'amount' => $amount,
                'due_date' => $investmentPayment->due_date,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function edit(PropertyPayment $payment)
    {
        return view('admin.crm.client.payments.modal', ['payment' => $payment, 'property_id' => $payment->property_id]);
    }

    public function calcPercent(Request $request)
    {
        if ($request->ajax()) {
            $validated = $request->validate([
                'property_id' => 'required|integer', // property_id must be an integer and is required
                'percent' => 'required|numeric'    // percent must be numeric and is required
            ]);
            $property = Property::find($validated['property_id']);

            if ($property) {

                $priceBrutto = $property->price_brutto;
                if(!$priceBrutto){
                    $priceBrutto = $property->price;
                }
                $totalPayments = $property->payments->sum('amount');
                $remainingAmount = $priceBrutto - $totalPayments;

                $percentValue = $priceBrutto * ($validated['percent'] / 100);

                return response()->json([
                    'status' => 'success',
                    'percent_value' => $percentValue,
                    'price_brutto' => $priceBrutto,
                    'total_payments' => $totalPayments,
                    'remaining_amount' => $remainingAmount
                ]);

            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Property not found',
                ], 404);
            }
        }

        // If not an AJAX request, return an error response
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid request',
        ], 400);
    }

    public function calcAmount(Request $request)
    {
        if ($request->ajax()) {
            $validated = $request->validate([
                'property_id' => 'required|integer',
                'amount' => 'required|numeric'
            ]);
            $property = Property::find($validated['property_id']);

            if ($property) {

                $priceBrutto = $property->price_brutto;
                if(!$priceBrutto){
                    $priceBrutto = $property->price;
                }
                $totalPayments = $property->payments->sum('amount');
                $remainingAmount = $priceBrutto - $totalPayments;

                if ($remainingAmount <= 0) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No remaining amount to calculate percentage from.',
                    ], 400);
                }

                $percentValue = ($validated['amount'] / $priceBrutto) * 100;

                return response()->json([
                    'status' => 'success',
                    'percent_value' => round($percentValue),
//                    'price_brutto' => $priceBrutto,
//                    'total_payments' => $totalPayments,
//                    'remaining_amount' => $remainingAmount
                ]);

            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Property not found',
                ], 404);
            }
        }

        // If not an AJAX request, return an error response
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid request',
        ], 400);
    }

    public function update(PaymentRequest $request, PropertyPayment $payment)
    {
        if (request()->ajax()) {
            $this->repository->update($request->validated(), $payment);
            return response()->json(['success' => true]);
        }

        return response('This endpoint only supports AJAX requests.', 400);
    }

    public function generateTable(Client $client, Property $property)
    {
        $html = view('admin.crm.client.payments.table', ['property' => $property])->render();
        $nextPayment = $property->payments()->orderBy('due_date', 'asc')->where('due_date', '>=', Carbon::today())->first();
        $investmentPayments = $property->investmentPayments->count();
        $propertyPayments = $property->payments()->count();

        $additionalData = [
            'latestPayment' => $nextPayment?->due_date ?? '-',
            'latestAmount' => $nextPayment?->amount ? number_format($nextPayment->amount, 2, '.', ' ') . ' zł' : '-',
        ];

        return response()->json([
            'html' => $html,
            'data' => $additionalData,
            'investmentPayments' => $investmentPayments,
            'propertyPayments' => $propertyPayments
        ]);
    }

    public function destroy(PropertyPayment $payment)
    {
        $payment->delete();
        return response()->json(['success' => 'Payment deleted successfully.']);
    }
}
