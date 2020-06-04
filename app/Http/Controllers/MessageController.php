<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Event;
use App\Events\MessageEvent;

use App\Message;
use App\Http\Resources\MessageResource;
use App\ChatRoom;
use App\Http\Resources\ChatResource;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $chat)
    {
        $chat = ChatRoom::findOrFail($chat);
        if (
            !$chat->member1 === $request->user()->id ||
            !$chat->member2 === $request->user()->id
        ) {
            return response()->json([
                'error' => 'You\'re not a member of this chat.'
            ]);
        }

        if (!$request->message) {
            return response()->json([
                'error' => 'Please provide all fields'
            ]);
        }

        $message = Message::create([
            'user' => $request->user()->id,
            'chat_room' => $chat->id,
            'message' => $request->message
        ]);

        event(new MessageEvent($message));

        return new MessageResource($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
    }
}
