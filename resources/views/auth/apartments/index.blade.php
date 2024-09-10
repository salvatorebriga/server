<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      My Apartments
    </h2>
  </x-slot>

  <div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @forelse ($apartments as $apartment)
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
          <!-- Image -->
          <div class="relative">
            @if ($apartment->img)
              <img class="w-full h-48 object-cover object-center" src="{{ '' }}" alt="{{ $apartment->title }}">
            @else
              <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500">No Image</span>
              </div>
            @endif
          </div>

          <!-- Content -->
          <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $apartment->title }}</h3>
            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $apartment->address }}</p>

            <!-- Optional details -->
            <div class="mt-4 flex items-center justify-between">
              <span class="text-sm text-gray-600 dark:text-gray-400">Rooms: {{ $apartment->rooms }}</span>
              <span class="text-sm text-gray-600 dark:text-gray-400">Beds: {{ $apartment->beds }}</span>
            </div>
          </div>
        </div>
      @empty
        <div class="col-span-1">
          <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            <p class="text-gray-600 dark:text-gray-400">No apartments found.</p>
          </div>
        </div>
      @endforelse
    </div>
  </div>
</x-app-layout>
