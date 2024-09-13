<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Update Apartment
        </h2>
    </x-slot>

    @include('shared.errors')
    @include('share.success')


    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <form action="{{ route('apartments.update', $apartment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-4">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                        :value="old('title', $apartment->title)" required />
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <div class="relative w-full">
                        <x-input-label for="address" :value="__('Address')" />
                        <x-text-input id="address" class="block mt-1 w-full" type="text" name="address"
                            :value="old('address', $apartment->address)" oninput="getAutocomplete()" required autofocus />
                        <ul id="results"
                            class="absolute w-full bg-white mt-1 rounded-lg shadow-lg overflow-hidden z-10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </ul>
                    </div>
                </div>

                <!-- Image -->
                <div class="mb-4">
                    <x-input-label for="img" :value="__('Image')" />
                    <input type="file" name="img" id="img"
                        class="block w-full border border-gray-300 shadow-sm rounded-lg text-sm focus:z-10 focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-900 dark:border-gray-700 dark:text-neutral-300 file:bg-indigo-600 file:text-white file:border-0 file:me-4 file:py-3 file:px-4 dark:file:bg-indigo-600 dark:file:text-white">
                    @if ($apartment->img)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $apartment->img) }}" alt="{{ $apartment->title }}"
                                class="h-24">
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Current Image</p>
                        </div>
                    @endif
                </div>

                <!-- Rooms, Beds, and Bathrooms -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <x-input-label for="mq" :value="__('Mq')" />
                        <x-text-input id="mq" class="block mt-1 w-full"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="mq" :value="old('mq', $apartment->mq)"
                            required />
                    </div>
                    <div>
                        <x-input-label for="rooms" :value="__('Rooms')" />
                        <x-text-input id="rooms" class="block mt-1 w-full"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="rooms" :value="old('rooms', $apartment->rooms)"
                            required />
                    </div>
                    <div>
                        <x-input-label for="beds" :value="__('Beds')" />
                        <x-text-input id="beds" class="block mt-1 w-full"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="beds" :value="old('beds', $apartment->beds)"
                            required />
                    </div>
                    <div>
                        <x-input-label for="bathrooms" :value="__('Bathrooms')" />
                        <x-text-input id="bathrooms" class="block mt-1 w-full"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="bathrooms"
                            :value="old('bathrooms', $apartment->bathrooms)" required />
                    </div>
                </div>

                <!-- Checkbox per i servizi -->
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-400 mb-2">Servizi</label>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach ($services as $service)
                            <div>
                                <label
                                    class="flex p-3 w-full bg-white border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600">
                                    <input type="checkbox" name="services[]"
                                        class="shrink-0 mt-0.5 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-900 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                        value="{{ $service->id }}"
                                        {{ (is_array(old('services')) && in_array($service->id, old('services'))) || (isset($apartmentServices) && in_array($service->id, $apartmentServices)) ? 'checked' : '' }}>
                                    <span
                                        class="text-sm text-gray-500 ms-3 dark:text-neutral-400">{{ $service->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Availability -->
                <div class="mb-4">
                    <x-input-label for="is_available" :value="__('Available')" />
                    <select
                        class="px-4 pe-9 block w-full border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 dark:placeholder-neutral-500 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
                        name="is_available" id="is_available" required>
                        <option value="" disabled>Select availability</option>
                        <option value="1"
                            {{ old('is_available', $apartment->is_available) == '1' ? 'selected' : '' }}>Yes
                        </option>
                        <option value="0"
                            {{ old('is_available', $apartment->is_available) == '0' ? 'selected' : '' }}>No
                        </option>
                    </select>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <!-- Back Button -->
                    <a href="{{ route('apartments.index') }}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Back to List
                    </a>

                    <!-- Submit Button -->
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Update Apartment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
