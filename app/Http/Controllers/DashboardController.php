<?php

namespace App\Http\Controllers;

use App\Services\Google\MapsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function fetchAddress(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $maps = new MapsService($latitude, $longitude);
        $address = $maps->fetch();
        if ($address)
            return response()->json(['address' => $address]);

        return response()->json(['address' => 'Not found']);
    }
}
