<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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
}
