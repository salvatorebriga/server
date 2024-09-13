<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="max-w-4xl">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    My Apartments
                </h2>
            </div>
            <a href="{{ route('apartments.create') }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                + Create New Apartment
            </a>
        </div>
    </x-slot>
    @include('shared.success')

    <div class="max-w-7xl mx-auto px-4 py-12">
        @if ($apartments->isEmpty())
            <!-- No apartments found -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <p class="text-gray-600 dark:text-gray-400">No apartments found.</p>
            </div>
        @else
            <!-- Apartments grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($apartments as $apartment)
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                        <!-- Image -->
                        <div class="relative">
                            @if ($apartment->img)
                                <img class="w-full h-48 object-cover object-center"
                                    src="{{ asset('storage/' . $apartment->img) }}" alt="{{ $apartment->title }}">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">No Image</span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $apartment->title }}
                            </h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $apartment->address }}</p>

                            <!-- Optional details -->
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Rooms:
                                    {{ $apartment->rooms }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Beds:
                                    {{ $apartment->beds }}</span>
                            </div>

                            <!-- Action buttons (Show, Delete) -->
                            <div class="mt-4 flex justify-between">
                                <!-- Show button -->
                                <a href="{{ route('apartments.show', $apartment->id) }}"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Details
                                </a>

                                <!-- Destroy button with confirmation -->
                                <form action="{{ route('apartments.destroy', $apartment->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this apartment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
