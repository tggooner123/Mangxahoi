<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Post;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    protected $id;
    public function PostController() {
        $this->id = Auth::user()->id;
    }
    public function posts() {
        $posts = Post::with(['user'])->orderBy('created_at', 'desc')->get();
        return response()->json($posts);
    }
    public function postByUser($id) {
        $postsByUser = Post::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        return response()->json($postsByUser);
    }

    public function save(Request $request) {
        if($request->get('image'))
        {
          $image = $request->get('image');
          $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
          \Image::make($request->get('image'))->save(public_path('uploads/').$name);
        }
        $post = new Post();
        $post->user_id = $request->user_id; 
        $post->status = $request->status;
        $post->img = '/' . 'uploads/' . $name;
        
        $post->save();
        return response()->json($request);
    }

    public function savePostContent(Request $request) {
        $post = new Post();
        $post->user_id = $request->user_id; 
        $post->status = $request->status;
        $post->save();
        return response()->json($request);
    }

    public function savePostImage(Request $request) {
        if($request->get('image'))
        {
          $image = $request->get('image');
          $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
          \Image::make($request->get('image'))->save(public_path('uploads/').$name);
        }
        $post = new Post();
        $post->user_id = $request->user_id; 
        $post->img = '/' . 'uploads/'. $name;
        
        $post->save();
        return response()->json($request);
    }

    public function editPost(Request $request) {
        $post = Post::FindOrFail($request->id);
        $post->status = $request->status;
        $post->save();

    }

    public function deletePost(Request $request) {
        $comments = Comment::where('post_id', $request->id);
        $comments->delete();
        $post = Post::FindOrFail($request->id);
        if(File::exists(public_path($request->img))) {
            File::delete(public_path($request->img));
        };
        $post->delete();
        
    }

}
