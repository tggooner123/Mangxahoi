<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/home', 'HomeController@index')->middleware('auth')->name('home');
Route::get('/profile/{id}', 'HomeController@profile')->middleware('auth')->name('profile');
//Route post
Route::group([], function() {
    Route::post('/post', 'PostController@save');
    Route::post('/postContent', 'PostController@savePostContent');
    Route::post('/postImage', 'PostController@savePostImage');
    Route::post('/editPost', 'PostController@editPost');
    Route::post('/deletePost', 'PostController@deletePost');
    Route::post('/editComment', 'CommentController@editComment');
    Route::post('/deleteComment', 'CommentController@deleteComment');
    Route::post('/uploadAvatar', 'HomeController@uploadAvatar');
    Route::post('/friendRequest', 'FriendRequestController@saveRequest');
    Route::post('/addFriend', 'FriendController@addFriend');
    Route::post('/deleteRequest', 'FriendRequestController@deleteRequest');
    Route::post('/sendMessage', 'MessageController@save');
    Route::post('/search', 'HomeController@search');
});
//Route comment
Route::group([], function() {
    Route::post('/store', 'CommentController@storeComment');
    
});
//Search
Route::get('/search', 'HomeController@search');
Auth::routes();
//API
Route::group(['prefix' => '/api'], function() {
    Route::get('/profile', 'HomeController@getUser')->middleware('auth');
    Route::get('/profile/{id}', 'HomeController@getUserById')->middleware('auth');
    Route::get('/posts', 'PostController@posts')->middleware('auth');
    Route::get('/comments', 'CommentController@comments')->middleware('auth');
    Route::get('/postByUser/{id}', 'PostController@postByUser')->middleware('auth');
    Route::get('/friendRequest', 'FriendRequestController@requests')->middleware('auth');
    Route::get('/friend', 'FriendController@getFriends')->middleware('auth');
    Route::post('/getMessages', 'MessageController@messages')->middleware('auth');
    Route::get('/result', 'HomeController@resultSearch')->middleware('auth');

});

Route::get('/{url}', function ($name) {
    return view('welcome');
})->where('url', '[A-Za-z0-9\-]+');

