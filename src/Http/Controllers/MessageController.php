<?php

namespace Sunarc\ChatSystem\Http\Controllers;
use App\Http\Controllers\Controller;
use Sunarc\ChatSystem\Models\Message;
use Sunarc\ChatSystem\Models\User;
use Illuminate\Http\Request;
use App\Events\MessageSend;

class MessageController extends Controller
{
    public function user_list(Request $request){
        $users = User::latest()->where('id','!=',auth()->user()->id)->get();
        if(!$request->ajax()){
            return abort(404);
        }
        return response()->json($users,200);
    }
    public function user_message($id=null){
        $user = User::findOrFail($id);
        $messages = $this->message_by_user_id($id);
        if(\Request::ajax()){
            return response()->json([
                'messages'=>$messages,
                'user'=>$user,
            ]);
        }
        return abort(404);
    }
    public function send_message(Request $request){
        if(!$request->ajax()){
            return abort(404);
        }
        $messages = Message::create([
            'message'=>$request->message,
            'from'=>auth()->user()->id,
            'to'=>$request->user_id,
            'type'=>0
        ]);
        $messages = Message::create([
            'message'=>$request->message,
            'from'=>auth()->user()->id,
            'to'=>$request->user_id,
            'type'=>1
        ]);
        broadcast(new MessageSend($messages));
        return response()->json($messages,201);
    }
    public function delete_single_message(Request $request,$id=null){
        if(!$request->ajax()){
            return abort(404);
        }
        Message::findOrFail($id)->delete();
        return response()->json('deleted',200);
    }
    public function delete_all_message($id=null){
        $messages = $this->message_by_user_id($id);
        foreach($messages as $value){
            Message::findOrFail($value->id)->delete();
        }
        return response()->json('all deleted',200);
    }
    public function message_by_user_id($id){
        $messages = Message::where(function($q) use ($id) {
            $q->where('from',auth()->user()->id);
            $q->where('to',$id);
            $q->where('type',0);
        })->orWhere(function($q) use ($id) {
            $q->where('to',auth()->user()->id);
            $q->where('from',$id);
            $q->where('type',1);
        })->with('user')->get();
        return $messages;
    }
}