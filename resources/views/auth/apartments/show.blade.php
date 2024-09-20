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
                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Address:</strong> {{ $apartment->address }},
                    {{ $apartment->house_number }}, {{ $apartment->postal_code }}, {{ $apartment->country }}</p>
                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Latitude:</strong>
                    {{ $apartment->latitude ?? 'Not provided' }}</p>
                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Longitude:</strong>
                    {{ $apartment->longitude ?? 'Not provided' }}</p>
                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Rooms:</strong> {{ $apartment->rooms }}</p>
                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Beds:</strong> {{ $apartment->beds }}</p>
                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Bathrooms:</strong>
                    {{ $apartment->bathrooms }}</p>
                <p class="text-gray-700 dark:text-gray-400 mb-2"><strong>Square Meters:</strong> {{ $apartment->mq }}
                    m²</p>

                <!-- Services Section -->
                <div class="bg-white dark:bg-gray-800 py-2">
                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Services</h3>
                    @if ($apartment->services->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">No services available for this apartment.</p>
                    @else
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-400">
                            @foreach ($apartment->services as $service)
                                <li>{{ $service->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <p class="text-gray-700 dark:text-gray-400 mb-2">
                    <strong>Available:</strong>
                    @if ($apartment->is_available)
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
            <!-- Sponsorship Button -->
            <button id="sponsor-button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Sponsor
                Apartment</button>
            <!-- Edit Button -->
            <a href="{{ route('apartments.edit', $apartment->id) }}"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Edit Apartment
            </a>
        </div>
    </div>

    {{-- Message Section --}}
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Messages</h3>

            @if ($messages->isEmpty())
                <p class="text-gray-600 dark:text-gray-400">No messages available for this apartment.</p>
            @else
                <!-- Delete Selected Button -->
                <div class="mb-4">
                    <button onclick="deleteSelected()"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Delete Selected
                    </button>
                </div>

                <div class="space-y-4">
                    @foreach ($messages as $message)
                        <div
                            class="relative bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md w-full break-words hover:bg-gray-200 dark:hover:bg-gray-600">
                            <!-- Checkbox -->
                            <input type="checkbox"
                                class="dark:bg-gray-800 dark:border-none absolute top-1/2 left-6 cursor-pointer"
                                value="{{ $message->id }}" name="message_ids[]" />

                            <div class="flex justify-between items-center mb-4 ml-12">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $message->name }} {{ $message->surname }}
                                </h4>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $message->email }}</p>
                            </div>

                            <p class="text-gray-700 dark:text-gray-400 ml-12">
                                {{ $message->message }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Confirm Deletion</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Are you sure you want to delete the selected messages?</p>
            {{-- form --}}
            <form id="deleteForm" method="POST" action="{{ route('messages.destroyMultiple') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" id="messageIds" name="message_ids">
                <input type="hidden" id="returnUrl" name="return_url" value="{{ url()->current() }}">
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sponsorship Modal -->
    <div id="sponsorshipModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Select Sponsorship</h3>
            <div id="dropin-container"></div>
            <form id="sponsorship-form" method="POST" action="{{ route('sponsorship.store', $apartment->id) }}">
                @csrf
                <div>
                    <label class="flex items-center mb-2">
                        <input type="radio" name="sponsorship" value="basic"
                            class="mr-2 accent-green-500 dark:accent-green-400">
                        <span class="text-gray-700 dark:text-gray-200">Basic - €2.99 for 24 hours</span>
                    </label>
                    <label class="flex items-center mb-2">
                        <input type="radio" name="sponsorship" value="premium"
                            class="mr-2 accent-green-500 dark:accent-green-400">
                        <span class="text-gray-700 dark:text-gray-200">Premium - €5.99 for 72 hours</span>
                    </label>
                    <label class="flex items-center mb-2">
                        <input type="radio" name="sponsorship" value="exclusive"
                            class="mr-2 accent-green-500 dark:accent-green-400">
                        <span class="text-gray-700 dark:text-gray-200">Exclusive - €9.99 for 144 hours</span>
                    </label>
                </div>

                <div class="flex justify-between mt-4">
                    <button id="pay-button" type="submit"
                        class="px-4 py-2 bg-green-500 dark:bg-green-700 text-white hover:bg-green-600 dark:hover:bg-green-800 transition-colors hidden rounded-lg">
                        Pay Now
                    </button>

                    <button type="button" onclick="closeSponsorshipModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 dark:bg-gray-700 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://js.braintreegateway.com/web/dropin/1.43.0/js/dropin.js"></script>

    <script>
        var button = document.querySelector('#pay-button');
        var instance; // Variabile per l'istanza del Drop-in

        document.getElementById('sponsor-button').addEventListener('click', function() {
            document.getElementById('sponsorshipModal').classList.remove('hidden');

            // Inizializza il Drop-in quando la modale è aperta
            braintree.dropin.create({
                authorization: 'sandbox_g42y39zw_348pk9cgf3bgyw2b',
                selector: '#dropin-container'
            }, function(err, dropinInstance) {
                instance = dropinInstance;
            });
        });

        button.addEventListener('click', function() {
            instance.requestPaymentMethod(function(err, payload) {
                // Invia il nonce al server con il form
                var form = document.getElementById('sponsorship-form');
                var nonceInput = document.createElement('input');
                nonceInput.setAttribute('type', 'hidden');
                nonceInput.setAttribute('name', 'payment_method_nonce');
                nonceInput.setAttribute('value', payload.nonce);
                form.appendChild(nonceInput);
                form.submit(); // Invia il form
            });
        });

        function closeSponsorshipModal() {
            document.getElementById('sponsorshipModal').classList.add('hidden');
        }

        document.querySelectorAll('input[name="sponsorship"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.getElementById('pay-button').classList.remove('hidden');
            });
        });

        function closeModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
        }

        function deleteSelected() {
            const selected = Array.from(document.querySelectorAll('input[name="message_ids[]"]:checked')).map(checkbox =>
                checkbox.value);
            if (selected.length > 0) {
                document.getElementById('messageIds').value = selected.join(',');
                document.getElementById('confirmationModal').classList.remove('hidden');
            } else {
                alert("Please select at least one message to delete.");
            }
        }
    </script>
</x-app-layout>
