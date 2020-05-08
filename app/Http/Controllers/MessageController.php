<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Message;
use App\Http\Resources\MessageResource;
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
        //Get all to and from messages to current user
        return MessageResource::collection(
            Message::where('user_id', $request->user()->id)
                ->orWhere('to_id', $request->user()->id)
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
        // save new message and send broadcast + email
        if (!$request->to) {
            return response()->json(['error' => 'Please provide all fields']);
        }
        if (!$request->message) {
            return response()->json(['error' => 'Please provide all fields']);
        }

        $message = Message::create([
            'user_id' => $request->user()->id,
            'to_id' => $request->to,
            'message' => $request->message
        ]);

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
        // get all messages by specific user
        // dd(intval($id), $request->user()->id);
        if (intval($id) === $request->user()->id) {
            return response()->json(['error' => 'You can\'t message yourself']);
        }

        // dd(intval($id), $request->user()->id);

        // return MessageResource::collection(
        // Message::findOrFail(
        //     [
        //         'user_id' => $request->user()->id,
        //         'to_id' => intval($id)
        //     ],
        //     [
        //         'to_id' => $request->user()->id,
        //         'user_id' => intval($id)
        //     ]
        // )
        // ->orWhere([
        //     'to_id' => $request->user()->id,
        //     'user_id' => intval($id)
        // ])
        // ->latest()
        // ->get();
        // );

        return MessageResource::collection(
            Message::where(
                [
                    'user_id' => $request->user()->id,
                    'to_id' => intval($id)
                ],
                [
                    'user_id' => intval($id),
                    'to_id' => $request->user()->id
                ]
            )
                ->latest()
                ->get()
        );
    }
}
