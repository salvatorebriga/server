<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->back()->with('status', 'Message deleted successfully.');
    }

    public function destroyMultiple(Request $request)
    {
        $messageIds = $request->input('message_ids');
        $returnUrl = $request->input('return_url', url('/'));

        if (!empty($messageIds)) {
            Message::whereIn('id', explode(',', $messageIds))->delete();
            return redirect($returnUrl)->with('success', 'Messages deleted successfully.');
        }

        return redirect($returnUrl)->with('error', 'No messages selected.');
    }
}
