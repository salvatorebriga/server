<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function storeMessage(Request $request)
    {
        $validatedData = $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'email' => 'required|email|max:50',
            'message' => 'required|string',
        ]);

        $message = new Message();
        $message->apartment_id = $validatedData['apartment_id'];
        $message->name = $validatedData['name'];
        $message->surname = $validatedData['surname'];
        $message->email = $validatedData['email'];
        $message->message = $validatedData['message'];

        $message->save();

        return response()->json(['success' => 'Messaggio inviato con successo!'], 201);
    }
}
