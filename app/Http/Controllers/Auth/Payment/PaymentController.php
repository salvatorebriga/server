<?php

namespace App\Http\Controllers\Auth\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;  // Importa la classe Request per gestire le richieste HTTP
use Braintree\Gateway;       // Importa la classe Gateway di Braintree per gestire i pagamenti
use Illuminate\Support\Facades\Log; // Importa la classe Log per registrare errori e informazioni

class PaymentController extends Controller
{
    /**
     * Mostra il modulo di pagamento.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        // Restituisce la vista del modulo di pagamento
        return view('payment.form'); // Assicurati di avere una vista chiamata 'payment.form'
    }

    /**
     * Gestisce il pagamento e crea una transazione con Braintree.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        // Validazione dei dati della richiesta
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',                // L'importo deve essere un numero e maggiore di 0.01
            'payment_method_nonce' => 'required|string',           // Il nonce del metodo di pagamento deve essere una stringa
        ]);

        // Configura Braintree con le credenziali dell'ambiente
        $gateway = new Gateway([
            'environment' => config('services.braintree.environment'), // Ambiente di Braintree (sandbox o produzione)
            'merchantId' => config('services.braintree.merchant_id'),  // ID del commerciante Braintree
            'publicKey' => config('services.braintree.public_key'),     // Chiave pubblica Braintree
            'privateKey' => config('services.braintree.private_key'),   // Chiave privata Braintree
        ]);

        try {
            // Esegui la transazione di vendita con Braintree
            $result = $gateway->transaction()->sale([
                'amount' => $validated['amount'],                         // Importo della transazione
                'paymentMethodNonce' => $validated['payment_method_nonce'], // Nonce del metodo di pagamento
                'options' => [
                    'submitForSettlement' => true                        // Indica che la transazione deve essere inviata per la liquidazione
                ]
            ]);

            // Verifica se la transazione è riuscita
            if ($result->success) {
                // Se la transazione è riuscita, restituisci una risposta JSON con il risultato
                return response()->json(['success' => true, 'transaction_id' => $result->transaction->id]);
            } else {
                // Se la transazione non è riuscita, restituisci un messaggio di errore
                return response()->json(['success' => false, 'message' => $result->message]);
            }
        } catch (\Exception $e) {
            // Se si verifica un errore durante il processo di pagamento, registra l'errore e restituisci un messaggio di errore
            Log::error('Errore durante il pagamento: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Si è verificato un errore durante il pagamento.']);
        }
    }
}
