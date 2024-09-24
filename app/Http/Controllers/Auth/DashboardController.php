<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Statistic;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Recupera gli appartamenti dell'utente autenticato
        $apartments = Apartment::where('user_id', auth()->id())->get();

        // Statistiche generali
        $totalViews = Statistic::whereIn('apartment_id', $apartments->pluck('id'))->count();

        // Statistiche per ogni appartamento per le views odierne
        $todayViews = [];
        foreach ($apartments as $apartment) {
            $todayViews[$apartment->id] = Statistic::where('apartment_id', $apartment->id)
                ->whereDate('created_at', now()->toDateString())
                ->count();
        }

        // Conteggio totale dei messaggi per tutti gli appartamenti
        $totalMessages = $apartments->sum(function ($apartment) {
            return $apartment->messages()->count();
        });

        // Imposta un appartamento predefinito
        $apartmentId = $apartments->first()->id ?? null;
        $period = 'daily';
        $labels = [];
        $views = [];

        // Statistiche giornaliere per il grafico (ultimi 30 giorni)
        if ($apartmentId) {
            $dailyViews = Statistic::where('apartment_id', $apartmentId)
                ->where('created_at', '>=', now()->subDays(30))
                ->selectRaw("DATE(created_at) as date, COUNT(*) as views")
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();

            for ($i = 0; $i < 30; $i++) {
                $currentDay = now()->subDays($i)->format('Y-m-d');
                $labels[] = $currentDay;
                $views[] = $dailyViews->where('date', $currentDay)->first()->views ?? 0;
            }

            $labels = array_reverse($labels);
            $views = array_reverse($views);
        }

        // Calcola il totale delle visite per la settimana corrente e la settimana precedente
        $currentWeekViews = Statistic::where('apartment_id', $apartmentId)
            ->where('created_at', '>=', now()->startOfWeek())
            ->count();

        $previousWeekViews = Statistic::where('apartment_id', $apartmentId)
            ->where('created_at', '>=', now()->subWeeks(1)->startOfWeek())
            ->where('created_at', '<', now()->startOfWeek())
            ->count();

        // Calcolo percentuale dell'incremento/decremento
        $percentageChange = 0;
        if ($previousWeekViews > 0) {
            $percentageChange = (($currentWeekViews - $previousWeekViews) / $previousWeekViews) * 100;
        } elseif ($currentWeekViews > 0) {
            $percentageChange = 100; // Se ci sono state visite solo questa settimana
        }

        // Ritorna la vista della dashboard con le statistiche e gli appartamenti
        return view('dashboard', compact('apartments', 'totalViews', 'apartmentId', 'labels', 'views', 'period', 'todayViews', 'totalMessages', 'percentageChange'));
    }
}
