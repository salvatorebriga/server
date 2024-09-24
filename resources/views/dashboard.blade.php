<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Messaggio di benvenuto -->


      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Scheda Visitatori Totali -->
        <div
          class="bg-blue-100 dark:bg-blue-700 p-6 rounded-lg shadow-md transform hover:translate-y-1 transition-transform">
          <h4 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Total Visitors</h4>
          <p class="text-gray-600 dark:text-gray-300 mt-4">{{ $totalViews }}</p>
          <p class="text-gray-600 dark:text-gray-300 mt-2">{{ number_format($percentageChange) }}% this week</p>
        </div>

        <!-- Scheda Ricavi -->
        <div
          class="bg-green-100 dark:bg-green-700 p-6 rounded-lg shadow-md transform hover:translate-y-1 transition-transform">
          <h4 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Monthly Revenue</h4>
          <p class="text-gray-600 dark:text-gray-300 mt-4">$23,400</p>
          <p class="text-gray-600 dark:text-gray-300 mt-2">Up by 10% from last month</p>
        </div>

        <!-- Scheda Messaggi -->
        <div
          class="bg-yellow-100 dark:bg-yellow-700 p-6 rounded-lg shadow-md transform hover:translate-y-1 transition-transform">
          <h4 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Messages</h4>
          <p class="text-gray-600 dark:text-gray-300 mt-4"> You have {{ $totalMessages }} messages</p>
        </div>
      </div>

      <!-- Statistiche per gli appartamenti -->
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 mt-6">
        <h3 class="text-lg text-gray-800 dark:text-gray-200 font-semibold mb-4">Apartment Statistics</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach ($apartments as $apartment)
            <a href="{{ route('apartments.show', $apartment->id) }}">
              <div
                class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-md flex transform hover:translate-y-1 transition-transform">
                <!-- Immagine dell'appartamento -->
                <img src="{{ asset('storage/' . $apartment->img) }}" alt="{{ $apartment->title }}"
                  class="w-24 h-24 object-cover rounded-md mr-4">

                <!-- Dati appartamento -->
                <div class="flex-1">
                  <h4 class="text-gray-800 dark:text-gray-200">{{ $apartment->title }}</h4>
                  <p class="text-gray-600 dark:text-gray-400">Today's Views:
                    {{ $todayViews[$apartment->id] ?? 0 }}</p>

                  <!-- Minigrafico -->
                  <canvas id="chart-{{ $apartment->id }}" class="w-full h-32"></canvas>
                </div>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  <!-- Inclusione di Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4"></script>

  <!-- Script per il rendering dei grafici -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      @foreach ($apartments as $apartment)
        const ctx{{ $apartment->id }} = document.getElementById('chart-{{ $apartment->id }}').getContext(
          '2d');
        new Chart(ctx{{ $apartment->id }}, {
          type: 'bar',
          data: {
            labels: ['Today'],
            datasets: [{
              label: 'Views',
              data: [{{ $todayViews[$apartment->id] ?? 0 }}],
              backgroundColor: 'rgba(75, 192, 192, 0.6)',
              borderColor: 'rgba(75, 192, 192, 1)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
                suggestedMin: 0,
                suggestedMax: 3,
                ticks: {
                  stepSize: 1,
                  callback: function(value) {
                    return Number.isInteger(value) ? value :
                      null;
                  }
                }
              }
            },
          }
        });
      @endforeach
    });
  </script>

</x-app-layout>
