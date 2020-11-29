<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function storeComment(Request $request) {
        $comment = new Comment();
        $comment->post_id = $request->post_id;
        $comment->user_id = $request->user_id;
        $comment->content = $request->content;
        $comment->save();
    }

    public function comments() {
        $comments = Comment::with(['user'])->get();
        return response()->json($comments);
    }

    public function editComment(Request $request) {
        $comment = Comment::FindOrFail($request->id);
        $comment->content = $request->content;
        $comment->isEdit = 1;
        $comment->save();
    }

    public function deleteComment(Request $request) {
        $comment = Comment::FindOrFail($request->id);
        $comment->delete();
    }
}
