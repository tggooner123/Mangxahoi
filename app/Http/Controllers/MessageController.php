<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Auth;
use App\User;
use App\Events\MessagePosted;
class MessageController extends Controller
{
    public function save(Request $request) {
        $user = Auth::user();
        $message = new Message();
        $message->user_send = $request->user;
        $message->to_user = $request->friendUser;
        $message->message = $request->message;
        $message->save();
        broadcast(new MessagePosted($message, $user))->toOthers();
    }
    public function messages(Request $request) {
        $friend = User::find($request->id);
        $user = Auth::user();
        $messages = Message::where(function($query) use ($friend, $user) {
            $query->where(function($query) use($friend, $user) {
                $query->where('user_send', $user->id)->where('to_user', $friend->id);
            })->orWhere(function($query) use ($friend, $user) {
                $query->where('to_user', $user->id)->where('user_send', $friend->id);
            });
        })->get();
        return response()->json($messages);
    }
}
