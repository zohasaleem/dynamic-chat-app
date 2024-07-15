<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Events\MessageEvent;
use App\Events\MessageDeletedEvent;
use App\Events\MessageUpdatedEvent;


class UserController extends Controller
{
    public function loadDashboard(){

        $users = User::whereNotIn('id', [auth()->user()->id]);
        $count = $users->count();
        $users = $users->get();
        // return $users;
        return view('dashboard', compact('count', 'users'));
    }


    public function saveChat(Request $req){
        try{

            $chat =  Chat::create([
                'sender_id' => $req->sender_id,
                'receiver_id' => $req->receiver_id,
                'message' => $req->message,

            ]);

            event(new MessageEvent($chat));

            return response()->json([ 'success' => true, 'data' => $chat ]);
        }
        catch(\Exception $e){
            return response()->json([ 'success' => false, 'message' => $e->getMessage()]);
        }
    }



    //load chats
    public function loadChats(Request $req){
        try{

           $chats = Chat::where(function($query) use ($req){
                $query->where('sender_id', $req->sender_id)
                    ->orWhere('sender_id', $req->receiver_id);
           })->where(function($query) use ($req){
            $query->where('receiver_id', $req->sender_id)
                ->orWhere('receiver_id', $req->receiver_id);
           })->get();

        // query is something like this,  just for my understanding ---    where(sender_id = 1 OR sender_id = 2) AND (receiver_id = 1 OR receiver_id = 2)

            return response()->json([ 'success' => true, 'data' => $chats ]);
        }
        catch(\Exception $e){
            return response()->json([ 'success' => false, 'message' => $e->getMessage()]);
        }
    }




    //delete message
    public function deleteChat(Request $req){
        try{

           Chat::where('id', $req->id)->delete();

           event(new MessageDeletedEvent($req->id));


            return response()->json([ 'success' => true, 'message' => "Message deleted successfully!" ]);
        }
        catch(\Exception $e){
            return response()->json([ 'success' => false, 'message' => $e->getMessage()]);
        }
    }


     //delete message
     public function updateChat(Request $req){
        try{

           Chat::where('id', $req->id)->update(['message' => $req->message]);

           $chat = Chat::where('id', $req->id)->first();
           
           event(new MessageUpdatedEvent($chat));


            return response()->json([ 'success' => true, 'message' => "Message updated successfully!" ]);
        }
        catch(\Exception $e){
            return response()->json([ 'success' => false, 'message' => $e->getMessage()]);
        }
    }
    

}
