<?php

namespace App\Http\Controllers\Admin\Crm\Statistics;

use App\Charts\HourlyChart;
use App\Charts\RoomChart;
use App\Charts\SourceChart;
use App\Helpers\RoomStatusMaper;
use App\Http\Controllers\Controller;
use App\Models\ClientMessage;
use App\Models\Investment;
use App\Models\Property;
use App\Traits\InvestmentLinkTrait;
use App\Traits\PropertyLinkTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class IndexController extends Controller
{
    use PropertyLinkTrait, InvestmentLinkTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Carbon::setLocale('pl');
        $borderColors = [
            "rgba(255, 99, 132, 1.0)",
            "rgba(22,160,133, 1.0)",
            "rgba(255, 205, 86, 1.0)",
            "rgba(51,105,232, 1.0)",
            "rgba(244,67,54, 1.0)",
            "rgba(34,198,246, 1.0)",
            "rgba(153, 102, 255, 1.0)",
            "rgba(255, 159, 64, 1.0)",
            "rgba(233,30,99, 1.0)",
            "rgba(205,220,57, 1.0)"
        ];

        $fillColors = [
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",
            "rgba(205,220,57, 0.2)"
        ];

        $clientMessages = ClientMessage::all();

        $sources = [];
        $campaigns = [];

        foreach ($clientMessages as $clientMessage) {
            $arguments = json_decode($clientMessage->arguments, true);

            if (isset($arguments['dp_source'])) {
                $dpSource = $arguments['dp_source'];
                if (!in_array($dpSource, $sources)) {
                    $sources[] = $dpSource;
                }
            }

            if (isset($arguments['dp_campaign'])) {
                $dpCampaign = $arguments['dp_campaign'];
                if (!in_array($dpCampaign, $campaigns)) {
                    $campaigns[] = $dpCampaign;
                }
            }
        }

        $messages = ClientMessage::orderByDesc('created_at')->where('source_type', 1)->get();

        // Apply your filtering logic to $messages
        if ($request->filled('campaign')) {
            $campaign = $request->input('campaign');
            $messages = $messages->filter(function ($message) use ($campaign) {
                $arguments = json_decode($message->arguments, true);
                return isset($arguments['dp_campaign']) && $arguments['dp_campaign'] === $campaign;
            });
        }

        if ($request->filled('investment')) {
            $invest = $request->input('investment');
            $messages = $messages->filter(function ($message) use ($invest) {
                $arguments = json_decode($message->arguments, true);
                return isset($arguments['investment_id']) && $arguments['investment_id'] === $invest;
            });
        }

        if ($request->filled('source')) {
            $source = $request->input('source');
            $messages = $messages->filter(function ($message) use ($source) {
                $arguments = json_decode($message->arguments, true);
                return isset($arguments['dp_source']) && $arguments['dp_source'] === $source;
            });
        }

        if ($request->filled('date_from')) {
            $dateFrom = date('Y-m-d', strtotime($request->input('date_from')));
            $messages = $messages->filter(function ($message) use ($dateFrom) {
                return $message->created_at >= $dateFrom;
            });
        }

        if ($request->filled('date_to')) {
            $dateTo = date('Y-m-d', strtotime($request->input('date_to')));
            $messages = $messages->filter(function ($message) use ($dateTo) {
                return $message->created_at <= $dateTo;
            });
        }

        $messages_campaigns = $messages
            ->map(function ($message) {
                return json_decode($message->arguments, true);
            })
            ->filter(function ($arguments) {
                return isset($arguments['dp_campaign']);
            })
            ->map(function ($arguments) {
                return $arguments['dp_campaign'];
            })
            ->groupBy(function ($campaign) {
                return $campaign;
            })
            ->map->count();

        $campaigns_chart = new RoomChart();
        $campaigns_chart->labels($messages_campaigns->keys()->all());
        $campaigns_chart->dataset('Wiadomości', 'bar', $messages_campaigns->values()->all());

        $messages_sources = $messages
            ->map(function ($message) {
                return json_decode($message->arguments, true);
            })
            ->filter(function ($arguments) {
                return isset($arguments['dp_source']);
            })
            ->map(function ($arguments) {
                return $arguments['dp_source'];
            })
            ->groupBy(function ($source) {
                return $source;
            })
            ->map->count();

        $sources_chart = new SourceChart();
        $sources_chart->labels($messages_sources->keys()->all());
        $sources_chart->dataset('Wiadomości', 'bar', $messages_sources->values()->all());

        $messages_rooms = $messages
            ->filter(function ($message) {
                $arguments = json_decode($message->arguments, true);
                return isset($arguments['rooms']);
            })
            ->map(function ($message) {
                $arguments = json_decode($message->arguments, true);
                return $arguments['rooms'];
            })
            ->groupBy(function ($source) {
                return $source;
            })
            ->map->count();

        $rooms_chart = new RoomChart();
        $rooms_chart->labels($messages_rooms->keys()->all());
        $rooms_chart->dataset('Pokoje', 'bar', $messages_rooms->values()->all());

        $hourlyStatistics = $messages->groupBy(function ($message) {
            return $message->created_at->format('H');
        })->map->count();

        $hourly_chart = new HourlyChart();
        $hourly_chart->labels($hourlyStatistics->keys()->all());
        $hourly_chart->dataset('Godzina', 'bar', $messages_rooms->values()->all())
            ->color($borderColors)
            ->backgroundcolor($fillColors);

        $weeklyStatistics = $messages->groupBy(function ($message) {
            return $message->created_at->translatedFormat('l'); // 'l' returns the full day name (e.g., Monday, Tuesday)
        })->map->count();

        $weekly_chart = new HourlyChart();
        $weekly_chart->labels($weeklyStatistics->keys()->all());
        $weekly_chart->dataset('Dnia tygodnia', 'bar', $messages_rooms->values()->all());

        $monthlyStatistics = $messages->groupBy(function ($message) {
            return $message->created_at->translatedFormat('F'); // 'F' returns the full month name (e.g., January, February)
        })->map->count();

        $monthly_chart = new HourlyChart();
        $monthly_chart->labels($monthlyStatistics->keys()->all());
        $monthly_chart->dataset('Miesiące', 'bar', $messages_rooms->values()->all());

        $investments = Investment::all()->pluck('id', 'name');

        return view('admin.crm.statistics.index', compact([
            'sources',
            'campaigns',
            'messages',
            'messages_campaigns',
            'campaigns_chart',
            'messages_sources',
            'sources_chart',
            'messages_rooms',
            'rooms_chart',
            'hourlyStatistics',
            'hourly_chart',
            'weeklyStatistics',
            'weekly_chart',
            'monthlyStatistics',
            'monthly_chart',
            'investments'
        ]));
    }

    public function rooms(Request $request): \Illuminate\View\View
    {
        $validated = $request->validate([
            'room' => 'nullable|string',
            'status' => 'nullable|string',
            'investment' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $properties = Property::with(['investment'])
            ->select(['id', 'name', 'status', 'rooms', 'investment_id', 'views', 'created_at'])
            ->whereNot('storey_type', 2)
            ->get();

        $filteredProperties = $this->filterProperties(
            $properties,
            $validated
        );

        return view('admin.crm.statistics.rooms', [
            'investments' => Investment::pluck('id', 'name'),
            'uniqueRooms' => $this->getUniqueRooms($filteredProperties),
            'statusCounts' => $this->getStatusCounts($filteredProperties),
            'topProperties' => $this->getTopProperties($filteredProperties),
            'lowestProperties' => $this->getLowestProperties($filteredProperties),
            'reservedProperties' => $this->getReservedPropertiesData()
        ]);
    }
    private function getStatusCounts(Collection $properties): Collection
    {
        return $properties->pluck('status')->countBy()->sortKeys();
    }
    private function getTopProperties(Collection $properties): Collection
    {
        return $properties->sortByDesc('views')->take(10)->values();
    }
    private function getLowestProperties(Collection $properties): Collection
    {
        return $properties->sortBy('views')->take(10)->values();
    }
    private function getUniqueRooms(Collection $properties): Collection
    {
        return $properties->pluck('rooms')->unique()->values();
    }


    private function filterProperties(Collection $properties, array $filters): Collection
    {
        return $properties->when(
            isset($filters['room']),
            fn($collection) => $collection->where('rooms', $filters['room'])
        )->when(
            isset($filters['status']),
            fn($collection) => $collection->where('status', $filters['status'])
        )->when(
            isset($filters['investment']),
            fn($collection) => $collection->where('investment_id', $filters['investment'])
        )->when(
            isset($filters['date_from']),
            fn($collection) => $collection->filter(function ($item) use ($filters) {
                return Carbon::parse($item['created_at']) >= Carbon::parse($filters['date_from'])->startOfDay();
            })
        )->when(
            isset($filters['date_to']),
            fn($collection) => $collection->filter(function ($item) use ($filters) {
                return Carbon::parse($item['created_at']) <= Carbon::parse($filters['date_to'])->endOfDay();
            })
        );
    }

    private function getReservedPropertiesData()
    {

        $properties = Property::where('status', RoomStatusMaper::RESERVED)->get();
        // $properties = Property::all();
        // $properties = Property::whereHas('investment', function ($query){
        //     $query->where('type', 3);
        // })->with('investment')->get();


        return $properties->map(function (Property $property) {
            return [
                'id' => $property->id,
                'name' => $property->name,
                'investment' => $property->investment,
                'floor' => $property->floor->id ?? null,
                'client' => $property->client,
                'reserved_to' => $property->reservation_until,
                'link_to_client' => $property->client ? route('admin.crm.clients.show', $property->client->id) : null,
                'link_to_property' => $this->getLinkToProperty($property),
                'link_to_investment' => $this->getInvestmentLink($property->investment),

            ];
        })->toArray();
    }
}
