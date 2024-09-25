<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Apartment Details
        </h2>
    </x-slot>
    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('shared.errors')
            @include('shared.success')
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <!-- Centered Image Section -->
                    <div class="mb-8">

                        @if ($apartment->img)
                            <img src="{{ asset('storage/' . $apartment->img) }}" alt="Apartment Image"
                                class="w-full max-w-3xl mx-auto h-auto rounded-lg shadow-md">
                        @else
                            <div
                                class="w-full max-w-3xl mx-auto h-64 bg-gray-300 dark:bg-gray-700 rounded-lg shadow-md flex items-center justify-center">
                                <span class="text-gray-600 dark:text-gray-400">Image not available</span>
                            </div>
                        @endif
                    </div>

                    <!-- Title and Address -->
                    <div class="text-center mb-8">
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-4">{{ $apartment->title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $apartment->address }}, {{ $apartment->house_number }}, {{ $apartment->postal_code }},
                            {{ $apartment->country }}
                        </p>
                    </div>

                    <!-- Apartment Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Features -->
                        <div>
                            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Features</h4>
                            <div class="grid grid-cols-2 gap-4 lg:mt-14 sm:mt-0 md:mt-14">
                                <div class="flex items-center">
                                    <i class="fa-solid fa-person-shelter w-5 h-5 mr-2 text-indigo-500"></i>
                                    <span class="text-gray-700 dark:text-gray-300 ps-3">{{ $apartment->rooms }}
                                        Rooms</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fa-solid fa-bed w-5 h-5 mr-2 text-indigo-500"></i>
                                    <span class="text-gray-700 dark:text-gray-300 ps-3">{{ $apartment->beds }}
                                        Beds</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fa-solid fa-toilet w-5 h-5 mr-2 text-indigo-500"></i>
                                    <span class="text-gray-700 dark:text-gray-300 ps-3">{{ $apartment->bathrooms }}
                                        Bathrooms</span>
                                </div>

                                <div class="flex items-center">
                                    <i class="fa-solid fa-expand w-5 h-5 mr-2 text-indigo-500 "></i>
                                    <span class="text-gray-700 dark:text-gray-300 ps-3">{{ $apartment->mq }} m²</span>
                                </div>
                            </div>
                        </div>

                        <!-- Services -->
                        <div>
                            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Services</h4>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                                @if ($apartment->services->isEmpty())
                                    <p class="text-gray-600 dark:text-gray-400">No services available for this
                                        apartment.</p>
                                @else
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach ($apartment->services as $service)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span
                                                    class="text-gray-700 dark:text-gray-300">{{ $service->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Availability</h5>
                            <p class="text-xl font-bold">
                                @if ($apartment->is_available)
                                    <span class="text-green-600 dark:text-green-400">Available</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400">Not Available</span>
                                @endif
                            </p>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Views</h5>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ $apartment->statistics->count() }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Sponsored Time</h5>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ $totalHours }}h {{ $totalMinutes }}m
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap justify-between items-center gap-4 mt-8">
                        <a href="{{ route('apartments.index') }}"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 hover:translate-y-1 transition duration-300 ease-in-out text-sm">
                            Back to List
                        </a>

                        <div class="flex flex-grow justify-center gap-4">
                            <button id="sponsor-button"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 hover:translate-y-1 transition duration-300 ease-in-out text-sm">
                                Sponsor Apartment
                            </button>
                            <a href="{{ route('apartments.stats', $apartment->id) }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg  hover:bg-blue-700 hover:translate-y-1 transition duration-300 ease-in-out text-sm">
                                View Stats
                            </a>
                            <a href="{{ route('apartments.edit', $apartment->id) }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 hover:translate-y-1 transition duration-300 ease-in-out text-sm">
                                Edit Apartment
                            </a>
                        </div>

                        <button onclick="confirmDelete('{{ $apartment->id }}')"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 hover:translate-y-1 transition duration-300 ease-in-out text-sm">
                            Delete Apartment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Section -->
    <div class="py-12 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Messages</h3>

                    @if ($messages->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">No messages available for this apartment.</p>
                    @else
                        <div class="mb-4">
                            <button onclick="deleteSelected()"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300 ease-in-out">
                                Delete Selected
                            </button>
                        </div>

                        <div class="space-y-4">
                            @foreach ($messages as $message)
                                <div
                                    class="relative bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 ease-in-out">
                                    <input type="checkbox"
                                        class="absolute top-4 left-4 w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        value="{{ $message->id }}" name="message_ids[]">

                                    <div class="ml-8">
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $message->name }} {{ $message->surname }}
                                            </h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $message->email }}
                                            </p>
                                        </div>
                                        <p class="text-gray-700 dark:text-gray-300">{{ $message->message }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
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
    <div id="sponsorshipModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Select Sponsorship</h3>

            <!-- Contenitore per il Drop-in -->
            <div id="dropin-container"></div>

            <form id="sponsorship-form" method="POST" action="{{ route('sponsorship.store') }}">
                @csrf
                <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">
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


    <!-- Modale di conferma braintree-->
    <div id="confirmationModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Payment Successful!</h3>
            <p class="text-gray-700 dark:text-gray-200">Your payment has been processed successfully.</p>
            <button type="button" onclick="resetDropin()"
                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Close</button>
        </div>
    </div>

    <!-- Modale di errore braintree-->
    <div id="errorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Payment Rejected!</h3>
            <p class="text-gray-700 dark:text-gray-200">There was an error processing your payment. Please check your
                payment details and try again.</p>
            <button type="button" onclick="closeErrorModal()"
                class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Close</button>
        </div>
    </div>
    <!-- Modal delete apartment -->
    <div id="confirmModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg dark:text-white">
            <h2 class="text-lg font-semibold">Confirm Deletion</h2>
            <p class="mt-2">Are you sure you want to delete this apartment?</p>
            <div class="mt-4 flex justify-end">
                <button id="cancelBtn"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded mr-2">Cancel</button>
                <button id="confirmBtn" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
            </div>
        </div>
    </div>
    <script src="https://js.braintreegateway.com/web/dropin/1.43.0/js/dropin.js"></script>
    <script>
        ////////////////////////////Modal braintree////////////////////////////////////////////////

        var button = document.querySelector('#pay-button');
        var instance; // Variabile per l'istanza del Drop-in

        // Prevenzione del comportamento predefinito del form
        var form = document.getElementById('sponsorship-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Blocca il submit automatico del form
        });

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
                if (err) {
                    console.error(err);
                    showErrorModal(); // Mostra la modale di errore
                    return; // Blocca l'invio del form se c'è un errore
                }

                // Aggiungi il nonce al form solo se il pagamento va a buon fine
                var nonceInput = document.createElement('input');
                nonceInput.setAttribute('type', 'hidden');
                nonceInput.setAttribute('name', 'payment_method_nonce');
                nonceInput.setAttribute('value', payload.nonce);
                form.appendChild(nonceInput);

                // Nascondi le opzioni di sponsorizzazione e il bottone "Pay Now"
                document.querySelectorAll('input[name="sponsorship"], #pay-button').forEach(function(el) {
                    el.closest('label') ? el.closest('label').style.display = 'none' : el.style
                        .display = 'none';
                });

                // Mostra la modale di conferma
                document.getElementById('confirmationModal').classList.remove('hidden');

                // Chiudi automaticamente la modale dopo 10 secondi (10000 ms)
                setTimeout(function() {
                    document.getElementById('confirmationModal').classList.add('hidden');
                    resetDropin(); // Resetta l'istanza di Drop-in
                }, 10000);

                // Invia il form solo se il pagamento è riuscito
                form.submit();
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

        // Funzione per mostrare la modale di errore
        function showErrorModal() {
            document.getElementById('errorModal').classList.remove('hidden');
        }

        // Funzione per chiudere la modale di errore
        function closeErrorModal() {
            document.getElementById('errorModal').classList.add('hidden');
            resetDropin(); // Resetta l'istanza di Drop-in anche dopo un errore
        }

        // Funzione per resettare il Drop-in e preparare un nuovo pagamento
        function resetDropin() {
            // Elimina l'istanza corrente del Drop-in e nascondi la modale
            if (instance) {
                instance.teardown(function(teardownErr) {
                    if (teardownErr) {
                        console.error('Could not tear down Drop-in instance:', teardownErr);
                    } else {
                        // Ricrea l'istanza del Drop-in
                        braintree.dropin.create({
                            authorization: 'sandbox_g42y39zw_348pk9cgf3bgyw2b',
                            selector: '#dropin-container'
                        }, function(err, dropinInstance) {
                            instance = dropinInstance;
                        });

                        // Mostra di nuovo le opzioni di sponsorizzazione e il bottone "Pay Now"
                        document.querySelectorAll('input[name="sponsorship"], #pay-button').forEach(function(el) {
                            el.closest('label') ? el.closest('label').style.display = 'block' : el.style
                                .display = 'block';
                        });
                    }
                });
            }
        }

        ////////////////////////////Modal delete messages////////////////////////////////////////////////
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
        ////////////////////////////Modal delete apartment////////////////////////////////////////////////
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
</x-app-layout>
