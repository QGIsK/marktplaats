<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Haversini\Haversini;

use App\Ads;
use App\Http\Resources\AdsResource;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return AdsResource::collection(
            Ads::with('user')
                ->latest('featuredAt')
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
        if (
            !$request->title ||
            !$request->description ||
            !$request->postalCode
        ) {
            return response()->json(
                ['error' => 'Please provide all fields'],
                422
            );
        }

        $postal = DB::table('4pp')
            ->where('postcode', $request->postalCode)
            ->first();

        $lat = $postal->latitude;
        $long = $postal->longitude;

        $ad = Ads::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'postalCode' => $request->postalCode,
            'lat' => $lat,
            'long' => $long,
            'description' => $request->description,
            'image' => json_encode($request->image),
            'featuredAt' => Carbon::now()->toDateTimeString()
        ]);

        $ad->categories()->attach($request->categories);

        return new AdsResource($ad);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Ads $ad)
    {
        return new AdsResource($ad);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ads $ad)
    {
        if (Auth::user()->role === 2 || Auth::user()->id != $ad->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$request->title || !$request->description) {
            return response()->json(
                ['error' => 'Please provide all fields'],
                422
            );
        }

        $image = json_encode($request->image);
        $ad->update([
            'title' => $request->title,
            'postalCode' => $request->postalCode,
            'description' => $request->description,
            'image' => json_encode($request->image)
        ]);

        $ad->categories()->sync($request->categories);

        return new AdsResource($ad);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Ads $ad)
    {
        if (Auth::user()->role === 2 || Auth::user()->id != $ad->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $ad->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $maxDistance = $request->distance;

        $ads = AdsResource::collection(
            Ads::with('user')
                ->latest('featuredAt')
                ->get()
        );

        if (!$maxDistance || !$request->postalCode) {
            return response()->json($ads);
        }

        $postal = DB::table('4pp')
            ->where('postcode', $request->postalCode)
            ->first();

        $resp = [];

        foreach ($ads as $ad) {
            $distance = Haversini::calculate(
                $postal->latitude,
                $postal->longitude,
                $ad->lat,
                $ad->long,
                'km'
            );

            if ($distance < $maxDistance) {
                $resp[] = $ad;
            }
        }

        return response()->json($resp, 200);
    }
}
