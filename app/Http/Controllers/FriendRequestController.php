<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FriendRequest;
use Auth;
class FriendRequestController extends Controller
{
    public function requests() {
        $friendRequests = FriendRequest::where('to_user', Auth::user()->id)->with('user')->get();
        return response()->json($friendRequests);
    }

    public function saveRequest(Request $request) {
        $friendRequest = new FriendRequest();
        $friendRequest->user_send_request = $request->user_send;
        $friendRequest->to_user = $request->to_user;
        $friendRequest->save();
    }

    public function deleteRequest(Request $request) {
        $friendRequest = FriendRequest::findOrFail($request->id);
        $friendRequest->delete();
    }
}
