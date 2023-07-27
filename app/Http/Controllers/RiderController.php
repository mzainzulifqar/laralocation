<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RiderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if the user has the "rider" role
        if (!$user->hasRole('rider') && !$user->hasRole('super_admin')) {
            abort(403, 'You are not authorized to view this page as a rider.');
        }

        // Fetch all saved locations from the database
        $locations = Location::all();

        // Rider locations
        $riderLocations = $user->locations;

        return view('rider.index', compact('locations', 'riderLocations'));
    }

    public function getLocation(Request $request)
    {
        $user = Auth::user();

        // Check if the user has the "rider" or "admin" role
        if (!$user->hasRole('rider') && !$user->hasRole('super_admin')) {
            return response()->json(['error' => 'You are not authorized to access this resource.'], 403);
        }

        // Get the rider's latitude and longitude from the request
        $riderLatitude = $request->input('latitude');
        $riderLongitude = $request->input('longitude');

        // Fetch all saved locations from the database
        $locations = Location::all();

        // Calculate the distance between rider and each location and add a random title
        $locationsWithDistance = [];
        foreach ($locations as $location) {
            $distance = $this->calculateDistance($riderLatitude, $riderLongitude, $location->latitude, $location->longitude);
            $locationsWithDistance[] = [
                'id' => $location->id,
                'title' => $location->name, // Replace with your own random title logic
                'distance' => $distance,
            ];
        }

        // Sort locations by distance in ascending order
        usort($locationsWithDistance, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        return response()->json(['locations' => $locationsWithDistance]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return round($miles * 1.609344, 2); // Convert miles to kilometers and round to 2 decimal places
    }
}
