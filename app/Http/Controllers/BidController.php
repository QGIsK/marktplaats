<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Use App\Bid;
use App\Http\Resources\BidResource;

class BidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($ad)
    {
        // dd(Bid::where('ad_id', $ad)->with('user')->get());
        return BidResource::collection(Bid::where('ad_id', $ad)->with('user')->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function store(Request $request)
    {
        if(!$request->amount) {
            return response()->json(['error' => 'Please provide the amount']);
        }

        if(!$request->ad_id) {
            return response()->json(['error' => 'Please provide the ad Id']);
        }
        
        $bid = Bid::create([
            'amount' => $request->amount,
            'user_id' => $request->user()->id,
            'ad_id' => $request->ad_id
        ]);

        return new BidResource($bid);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Bid $bid)
    {
        return new BidResource($bid);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bid $bid)
    {
        if(Auth::user()->id != $bid->user_id ) {
            return response()->json(['error' => 'Unauthorized'], 403);    
        }
        
        $bid->delete();
        return response()->json(null, 204);
    }
}
