<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Message;
use App\Http\Resources\MessageResource;
use App\ChatRoom;
use App\Http\Resources\ChatResource;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        return ChatResource::collection(
            ChatRoom::where('member1', $request->user()->id)
                ->orWhere('member2', $request->user()->id)
                ->latest()
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->member2) {
            return response()->json(['error' => 'Please provide a recipient']);
        }

        $room = ChatRoom::where([
            'member1' => $request->user()->id,
            'member2' => $request->member2
        ])
            ->orWhere([
                'member1' => $request->member2,
                'member2' => $request->user()->id
            ])
            ->first();
        if ($room) {
            return response()->json([
                'error' => 'A room with these members already exist'
            ]);
        }

        $chat = ChatRoom::create([
            'member1' => $request->user()->id,
            'member2' => $request->member2
        ]);

        return new ChatResource($chat);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $chat = ChatRoom::findOrFail($id);

        if (
            !$chat->member1 === $request->user()->id ||
            !$chat->member2 === $request->user()->id
        ) {
            return response()->json([
                'error' => 'You\'re not a member of this chat.'
            ]);
        }

        $messages = Message::where('chat_room', $chat->id)
            ->latest()
            ->get();

        return response()->json([
            'chat' => $chat,
            'messages' => $messages
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
