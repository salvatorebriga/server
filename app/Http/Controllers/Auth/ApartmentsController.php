<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Message;
use App\Models\Service;
use App\Models\Statistic;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ApartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apartments = Apartment::where('user_id', auth()->id())->get();

        return view('auth.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();
        return view('auth.apartments.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:250',
                'img' => 'nullable|image',
                'address' => 'required|max:100',
                'rooms' => 'required|integer|min:1',
                'beds' => 'required|integer|min:1',
                'bathrooms' => 'required|integer|min:1',
                'mq' => 'required|integer|min:1',
                'is_available' => 'required|boolean',
                'services' => 'required|min:1|array|exists:services,id',
            ]);

            $client = new Client(['verify' => false]);
            $apiKey = config('services.tomtom.key');
            $queryParams = [
                'query' => urlencode($validated['address']),
                'key' => $apiKey,
            ];
            $url = 'https://api.tomtom.com/search/2/geocode/' . $queryParams['query'] . '.json?view=Unified&key=' . $queryParams['key'];

            Log::info('TomTom API URL: ' . $url);

            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);

            if (isset($data['results'][0]['position'])) {
                $latitude = $data['results'][0]['position']['lat'];
                $longitude = $data['results'][0]['position']['lon'];
            } else {
                return redirect()->back()->withErrors('Indirizzo non trovato');
            }

            $apartment = new Apartment();
            $apartment->user_id = auth()->id();
            $apartment->title = $validated['title'];
            $apartment->img = $request->file('img') ? $request->file('img')->store('images', 'public') : null;
            $apartment->address = $validated['address'];
            $apartment->latitude = $latitude;
            $apartment->longitude = $longitude;
            $apartment->rooms = $validated['rooms'];
            $apartment->beds = $validated['beds'];
            $apartment->bathrooms = $validated['bathrooms'];
            $apartment->mq = $validated['mq'];
            $apartment->is_available = (bool) $validated['is_available'];

            $apartment->save();

            if ($request->has('services')) {
                $apartment->services()->sync($request->input('services'));
            }

            return redirect()->route('apartments.index')->with('success', 'Appartamento creato con successo');
        } catch (\Exception $e) {
            Log::error('Error creating apartment: ' . $e->getMessage());
            return redirect()->back()->withErrors('Si è verificato un errore durante la creazione dell\'appartamento.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id, Request $request)
    {
        // Recupera l'appartamento tramite l'ID
        $apartment = Apartment::with('sponsors')->findOrFail($id);

        // Recupera i messaggi associati all'appartamento
        $messages = Message::where('apartment_id', $apartment->id)->get();

        // Recupera le sponsorizzazioni attive (con 'end_date' maggiore della data attuale)
        $activeSponsors = $apartment->sponsors()->wherePivot('end_date', '>', now())->get();

        // Calcola il tempo totale accumulato
        $totalTime = $activeSponsors->sum(function ($sponsor) {
            return $sponsor->time; // Usa il campo 'time' del modello Sponsor
        });

        // Converti il tempo totale in ore e minuti
        $totalHours = floor($totalTime / 3600);
        $totalMinutes = ($totalTime % 3600) / 60;

        // Passa l'appartamento, i messaggi, le sponsorizzazioni attive, il tempo totale alla vista
        return view('auth.apartments.show', compact('apartment', 'messages', 'activeSponsors', 'totalHours', 'totalMinutes'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $apartment = Apartment::findOrFail($id);
        $services = Service::all();
        $apartmentServices = $apartment->services->pluck('id')->toArray();

        return view('auth.apartments.edit', compact('apartment', 'services', 'apartmentServices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:250',
            'img' => 'nullable|image',
            'address' => 'required|max:100',
            'rooms' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'mq' => 'required|integer|min:1',
            'is_available' => 'required|boolean',
            'services' => 'required|min:1|array|exists:services,id',
        ]);

        $apartment = Apartment::findOrFail($id);

        $addressChanged = $apartment->address !== $validated['address'];

        $apartment->title = $validated['title'];
        $apartment->address = $validated['address'];
        $apartment->rooms = $validated['rooms'];
        $apartment->beds = $validated['beds'];
        $apartment->bathrooms = $validated['bathrooms'];
        $apartment->mq = $validated['mq'];
        $apartment->is_available = $validated['is_available'];

        if ($request->hasFile('img')) {
            if ($apartment->img) {
                Storage::disk('public')->delete($apartment->img);
            }
            $apartment->img = $request->file('img')->store('images', 'public');
        }

        if ($addressChanged) {
            try {
                $client = new Client(['verify' => false]);
                $apiKey = config('services.tomtom.key');
                $queryParams = [
                    'query' => urlencode($validated['address']),
                    'key' => $apiKey,
                ];
                $url = 'https://api.tomtom.com/search/2/geocode/' . $queryParams['query'] . '.json?view=Unified&key=' . $queryParams['key'];

                Log::info('TomTom API URL: ' . $url);

                $response = $client->get($url);
                $data = json_decode($response->getBody(), true);

                if (isset($data['results'][0]['position'])) {
                    $apartment->latitude = $data['results'][0]['position']['lat'];
                    $apartment->longitude = $data['results'][0]['position']['lon'];
                } else {
                    return redirect()->back()->withErrors('Indirizzo non trovato');
                }
            } catch (\Exception $e) {
                Log::error('Error updating apartment coordinates: ' . $e->getMessage());
                return redirect()->back()->withErrors('Si è verificato un errore durante l\'aggiornamento delle coordinate.');
            }
        }

        $apartment->save();

        if ($request->has('services')) {
            $apartment->services()->sync($request->input('services'));
        }

        return redirect()->route('apartments.index')->with('success', 'Appartamento aggiornato con successo');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $apartment = Apartment::findOrFail($id);

        if ($apartment->img) {
            Storage::disk('public')->delete($apartment->img);
        }

        $apartment->delete();

        return redirect()->route('apartments.index')->with('success', 'Appartamento eliminato con successo');
    }

    public function stats($id, Request $request)
    {
        $apartment = Apartment::findOrFail($id);
        $period = $request->input('period', 'daily');

        if ($period === 'daily') {
            $startDate = now()->subDays(30);
            $groupBy = 'DATE(created_at)';
        } elseif ($period === 'weekly') {
            $startDate = now()->subWeeks(4)->startOfWeek();
            $groupBy = 'YEARWEEK(created_at, 1)';
        } else {
            $startDate = now()->subMonths(12)->startOfMonth();
            $groupBy = 'DATE_FORMAT(created_at, "%Y-%m")';
        }

        $dailyViews = Statistic::where('apartment_id', $id)
            ->where('created_at', '>=', $startDate)
            ->selectRaw("$groupBy as date, COUNT(*) as views")
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $labels = [];
        $views = [];

        if ($period === 'daily') {
            for ($i = 0; $i < 30; $i++) {
                $currentDay = now()->subDays($i)->format('Y-m-d');
                $labels[] = $currentDay;
                $views[] = $dailyViews->where('date', $currentDay)->first()->views ?? 0;
            }
        } elseif ($period === 'weekly') {
            for ($i = 0; $i < 4; $i++) {
                $currentWeek = now()->subWeeks($i)->startOfWeek()->format('Y-m-d');
                $labels[] = "Week of " . $currentWeek;
                $views[] = $dailyViews->where('date', now()->subWeeks($i)->format('oW'))->sum('views') ?? 0;
            }
        } else {
            for ($i = 0; $i < 12; $i++) {
                $currentMonth = now()->subMonths($i)->format('Y-m');
                $labels[] = now()->subMonths($i)->format('F Y');
                $views[] = $dailyViews->where('date', $currentMonth)->sum('views') ?? 0;
            }
        }

        $labels = array_reverse($labels);
        $views = array_reverse($views);

        $totalViews = Statistic::where('apartment_id', $id)->count();

        return view('auth.apartments.stats', compact('apartment', 'labels', 'views', 'totalViews', 'period'));
    }
}
