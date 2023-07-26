<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\Google\MapsService;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{

    public function store(Request $request)
    {
        // Validate and get latitude and longitude from the request
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string',
        ]);

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $address = $request->input('address');

        // Get the authenticated user
        $user = auth()->user();

        // Save the location to the database
        $location = new Location();
        $location->latitude = $latitude;
        $location->longitude = $longitude;
        $location->address = $address;
        $location->name = Str::random(10);
        $user->locations()->save($location);

        return redirect()->back()->with('success', 'Location saved');
    }

    public function fetchAddress(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $maps = new MapsService($latitude, $longitude);
        $address = $maps->fetch();
        if ($address)
            return response()->json(['address' => $address]);

        return response()->json(['address' => 'Not found']);
    }
}
