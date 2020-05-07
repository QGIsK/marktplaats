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
        if (!$request->from || !$request->to || $request->message) {
            return response()->json(['error' => 'Please provide all fields']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get all messages by specific user
    }
}
