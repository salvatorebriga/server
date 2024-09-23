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
   
    <div class="max-w-7xl mx-auto px-4 py-12">
        @if ($apartments->isEmpty())
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <p class="text-gray-600 dark:text-gray-400">No apartments found.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($apartments as $apartment)
                    <div
                        class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden flex flex-col transition-transform transform hover:translate-y-2 hover:scale-105 duration-300 ease-in-out relative group">
                        <a href="{{ route('apartments.show', $apartment->id) }}" class="block">
                            <div class="relative flex-shrink-0">
                                @if ($apartment->img)
                                    <img class="w-full h-48 object-cover object-center"
                                        src="{{ asset('storage/' . $apartment->img) }}" alt="{{ $apartment->title }}">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500">No Image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $apartment->title }}</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $apartment->address }}</p>

                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Rooms:
                                        {{ $apartment->rooms }}</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Beds:
                                        {{ $apartment->beds }}</span>
                                </div>
                            </div>
                        </a>

                        <!-- X Button, shown on hover -->
                        <button onclick="confirmDelete('{{ $apartment->id }}')"
                            class="absolute top-0 right-0 mt-2 mr-2 w-8 h-8 flex items-center justify-center rounded-full bg-red-600 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-in-out">
                            &times;
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div id="confirmModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg">
            <h2 class="text-lg font-semibold">Confirm Deletion</h2>
            <p class="mt-2">Are you sure you want to delete this apartment?</p>
            <div class="mt-4 flex justify-end">
                <button id="cancelBtn"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded mr-2">Cancel</button>
                <button id="confirmBtn" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
            </div>
        </div>
    </div>

    <script>
        let apartmentIdToDelete;

        function confirmDelete(apartmentId) {
            apartmentIdToDelete = apartmentId;
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        document.getElementById('cancelBtn').onclick = function() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        document.getElementById('confirmBtn').onclick = function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/apartments/' + apartmentIdToDelete;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    </script>

    <style scoped>
        .button-del {
            background-color: #fb0202;
            border-radius: 100px;
            box-shadow: rgba(244, 232, 232, 0.2) 0 -25px 18px -14px inset, rgba(187, 44, 44, 0.15) 0 1px 2px, rgba(187, 44, 44, 0.15) 0 2px 4px, rgba(187, 44, 44, 0.15) 0 4px 8px, rgba(187, 44, 44, 0.15) 0 8px 16px, rgba(187, 44, 44, 0.15) 0 16px 32px;
            color: rgb(251, 248, 248);
            cursor: pointer;
            display: inline-block;
            font-family: CerebriSans-Regular, -apple-system, system-ui, Roboto, sans-serif;
            padding: 7px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 250ms;
            border: 0;
            font-size: 16px;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .button-del:hover {
            box-shadow: rgba(253, 1, 1, 0.35) 0 -25px 18px -14px inset, rgba(187, 44, 44, 0.25) 0 1px 2px, rgba(187, 44, 44, 0.25) 0 2px 4px, rgba(187, 44, 44, 0.25) 0 4px 8px, rgba(187, 44, 44, 0.25) 0 8px 16px, rgba(187, 44, 44, 0.25) 0 16px 32px;
            transform: scale(1.05) rotate(-1deg);
        }
    </style>








</x-app-layout>
