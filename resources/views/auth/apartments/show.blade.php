<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Apartment Details
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 flex">
            <!-- Image Section -->
            <div class="w-1/2 pr-6">
                @if ($apartment->img)
                    <img src="{{ asset('storage/' . $apartment->img) }}" alt="Apartment Image"
                        class="w-full h-auto rounded-lg">
                @else
                    <div class="w-full h-64 bg-gray-300 text-center flex items-center justify-center rounded-lg">
                        <span class="text-gray-600 dark:text-gray-400">Image not available</span>
                    </div>
                @endif
            </div>

            <!-- Details Section -->
            <div class="w-1/2">
                <h3 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-4">{{ $apartment->title }}</h3>

                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Address:</strong> {{ $apartment->address }}</p>

                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Latitude:</strong>
                    {{ $apartment->latitude ?? 'Not provided' }}</p>

                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Longitude:</strong>
                    {{ $apartment->longitude ?? 'Not provided' }}</p>

                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Rooms:</strong> {{ $apartment->rooms }}</p>

                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Beds:</strong> {{ $apartment->beds }}</p>

                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Bathrooms:</strong> {{ $apartment->bathrooms }}
                </p>

                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Square Meters:</strong> {{ $apartment->mq }} m²
                </p>

                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Available:</strong>
                    @if ($apartment->is_avaible)
                        <span class="text-green-500">Yes</span>
                    @else
                        <span class="text-red-500">No</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="flex justify-between items-center mt-6">
            <!-- Back Button -->
            <a href="{{ route('apartments.index') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Back to List
            </a>

            <!-- Edit Button -->
            <a href="{{ route('apartments.edit', $apartment->id) }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Edit Apartment
            </a>
        </div>
    </div>
</x-app-layout>
