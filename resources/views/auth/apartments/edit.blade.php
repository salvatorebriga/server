<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Update Apartment
        </h2>
    </x-slot>
    @include('shared.errors')
    {{-- IL FORM DEVE PUNTARE ALL'UPDATE --}}
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <form action="{{ route('apartments.update', $apartment->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Title -->
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-400" for="title">Title</label>
                    <input class="w-full mt-2 p-2 border rounded-lg" type="text" name="title" id="title"
                        value="{{ old('title', $apartment->title) }}" required>
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-400" for="address">Address</label>
                    <input class="w-full mt-2 p-2 border rounded-lg" type="text" name="address" id="address"
                        value="{{ old('address', $apartment->address) }}" required>
                </div>

                <!-- Image -->
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-400" for="img">Image</label>
                    <input class="w-full mt-2 p-2" type="file" name="img" id="img">
                    @if ($apartment->img)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $apartment->img) }}" alt="{{ $apartment->title }}"
                                class="h-24">
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Current Image</p>
                        </div>
                    @endif
                </div>

                <!-- Latitude and Longitude -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-400" for="latitude">Latitude</label>
                        <input class="w-full mt-2 p-2 border rounded-lg" type="text" name="latitude" id="latitude"
                            value="{{ old('latitude', $apartment->latitude) }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-400" for="longitude">Longitude</label>
                        <input class="w-full mt-2 p-2 border rounded-lg" type="text" name="longitude" id="longitude"
                            value="{{ old('longitude', $apartment->longitude) }}">
                    </div>
                </div>

                <!-- Rooms, Beds, and Bathrooms -->
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 dark:text-gray-400" for="mq">Mq</label>
                        <input class="w-full mt-2 p-2 border rounded-lg"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="mq" id="mq"
                            value="{{ old('mq', $apartment->mq) }}" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-400" for="rooms">Rooms</label>
                        <input class="w-full mt-2 p-2 border rounded-lg"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="rooms" id="rooms"
                            value="{{ old('rooms', $apartment->rooms) }}" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-400" for="beds">Beds</label>
                        <input class="w-full mt-2 p-2 border rounded-lg"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="beds" id="beds"
                            value="{{ old('beds', $apartment->beds) }}" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 dark:text-gray-400" for="bathrooms">Bathrooms</label>
                        <input class="w-full mt-2 p-2 border rounded-lg"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');" name="bathrooms" id="bathrooms"
                            value="{{ old('bathrooms', $apartment->bathrooms) }}" required>
                    </div>
                </div>

                <!-- Availability -->
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-400" for="is_avaible">Available</label>
                    <select class="w-full mt-2 p-2 border rounded-lg" name="is_avaible" id="is_avaible" required>
                        <option value="" disabled>Select availability</option>
                        <option value="1"
                            {{ old('is_avaible', $apartment->is_avaible) == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0"
                            {{ old('is_avaible', $apartment->is_avaible) == '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Action Buttons -->
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
