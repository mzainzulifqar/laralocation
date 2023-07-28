<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::all();
        return view('medicines.index', compact('medicines'));
    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255|unique:medicines',
            'quantity' => 'required|integer',
        ]);

        // Create the new Medicine
        $medicine = new Medicine([
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'),
        ]);

        // Save the Medicine to the database
        $medicine->save();

        // Redirect back with a success message
        return redirect()->route('medicines.index')->with('success', 'Medicine created successfully.');
    }

    public function update(Request $request, Medicine $medicine)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|unique:medicines,name,' . $medicine->id,
            'quantity' => 'nullable|string|min:8|confirmed',
        ]);

        // Update the Medicine data
        $medicine->name = $request->input('name');
        $medicine->quantity = $request->input('quantity');

        // Save the updated medicine to the database
        $medicine->save();

        // Redirect back with a success message
        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        // Delete the Medicine from the database
        $medicine->delete();

        // Redirect back with a success message
        return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully.');
    }
}
