<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index()
    {
        $Medicines = Medicine::all();
        return view('medicines.index', compact('Medicines'));
    }

    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255|unique:Medicines',
            'quantity' => 'required|integer',
        ]);

        // Create the new Medicine
        $Medicine = new Medicine([
            'name' => $request->input('name'),
            'quantity' => $request->input('quantity'),
        ]);

        // Save the Medicine to the database
        $Medicine->save();

        // Redirect back with a success message
        return redirect()->route('medicines.index')->with('success', 'Medicine created successfully.');
    }

    public function update(Request $request, Medicine $Medicine)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required|string|unique:Medicines,name,' . $Medicine->id,
            'quantity' => 'nullable|string|min:8|confirmed',
        ]);

        // Update the Medicine data
        $Medicine->name = $request->input('name');
        $Medicine->quantity = $request->input('quantity');

        // Save the updated Medicine to the database
        $Medicine->save();

        // Redirect back with a success message
        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $Medicine)
    {
        // Delete the Medicine from the database
        $Medicine->delete();

        // Redirect back with a success message
        return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully.');
    }
}
