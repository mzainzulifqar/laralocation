<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Google\MapsService;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
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
