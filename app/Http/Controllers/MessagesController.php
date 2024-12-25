<?php

namespace App\Http\Controllers;

use App\Events\MessageEvent;
use App\Models\ChatChannel;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{

    public function chat_page()
    {
        $users = User::where('id', '!=', Auth::user()->id)->get();
        return view('chat.chat', compact('users'));
    }

    public function message_page($id)
    {

        $chatChannel = ChatChannel::where('from_user_id', Auth::user()->id)->where('to_user_id', $id)
            ->orWhere('from_user_id', $id)->where('to_user_id', Auth::user()->id)
            ->first();

        $users = User::where('id', '!=', Auth::user()->id)->get();
        $chattinguser = User::where('id', $id)->first();

        $chatChannel = ChatChannel::where(function ($query) use ($id) {
            $query->where('from_user_id', Auth::user()->id)
                ->where('to_user_id', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('from_user_id', $id)
                ->where('to_user_id', Auth::user()->id);
        })->first();

        if (is_null($chatChannel)) {
            $chatChannel = ChatChannel::create([
                'from_user_id' => Auth::user()->id,
                'to_user_id' => $id
            ]);
        }
        $messages = Message::where('chat_channel_id', $chatChannel->id)->orderBy('created_at', 'desc')->get();
        return view('chat.message', [
            'messages' => $messages,
            'users' => $users ?? [],
            'chatChannel' => $chatChannel,
            'chattinguser' => $chattinguser
        ]);
    }

    public function message_store(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'chat_channel_id' => 'required',
            'file_path' => 'nullable|file|mimes:jpg,mp4,jpeg,png,gif,pdf,doc,docx,txt|max:10048', // Adjust allowed file types and size as needed
        ]);

        $filePath = null;

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filePath = $file->store('uploads/messages', 'public'); 
        }

        $message = Message::create([
            'chat_channel_id' => $request->chat_channel_id,
            'text' => $request->text,
            'file_path' => $filePath, 
        ]);

        broadcast(new MessageEvent($message))->toOthers();

        return redirect()->back();
    }

}
