<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friend;
use App\Models\FriendRequest;
use Auth;

class FriendController extends Controller
{
    public function getFriends(Request $request) {
        $friends = Friend::where('user_id', Auth::user()->id)->with('user2')->orWhere('friend_id', Auth::user()->id)->with('user1')->get();
        return response()->json($friends);
    }
    
    public function addFriend(Request $request) {
        //insert to friend table
        $friend = new Friend();
        $friend->user_id = $request->user_send_request;
        $friend->friend_id = $request->to_user;
        $friend->save();
        //delete this friend request
        $friendRequest = FriendRequest::findOrFail($request->id);
        $friendRequest->delete();
    }

}
