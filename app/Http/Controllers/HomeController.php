<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $name = '';
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function profile($id) {
        
        return view('profile');
    }

    public function getUser(Request $request) {
        return response()->json(Auth::user());
    }
    public function getUserById(Request $request, $id) {
        return response()->json(User::find($id));
    }

    public function uploadAvatar(Request $request) {
        if($request->get('image'))
        {
          $image = $request->get('image');
          $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
          \Image::make($request->get('image'))->save(public_path('uploads/').$name);
        }
        $user = User::find($request->id);
        $user->avatar = '/' . 'uploads/'. $name;
        
        $user->save();
    }
    public function search(Request $request) {
        $this->name = $request->name;
        return view('result-search');
    }
    public function resultSearch() {
        $user = User::where('name', 'LIKE', $this->name);
        return response()->json($user);
    }
}
