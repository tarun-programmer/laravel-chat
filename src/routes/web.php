<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

Route::group(['namespace'=>'Sunarc\ChatSystem\Http\Controllers','middleware' => ['web', 'auth']],function(){
    Route::get('chat', function () {
        return view('ChatSystem::chatsystem');
    });
    Route::get('/userlist', 'MessageController@user_list')->name('user.list');
    Route::get('/usermessage/{id}', 'MessageController@user_message')->name('user.message');
    Route::post('/sendmessage', 'MessageController@send_message')->name('user.message.send');
    Route::get('/deletesinglemessage/{id}', 'MessageController@delete_single_message')->name('user.message.delete.single');
    Route::get('/deleteallmessage/{id}', 'MessageController@delete_all_message')->name('user.message.delete.all');
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('typingevent', function ($user) {
    return Auth::check();
});

Broadcast::channel('liveuser', function ($user) {
    return $user;
});