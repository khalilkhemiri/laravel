<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advice;
use App\Models\Plant;
use Illuminate\Support\Facades\Auth;

class AdviceController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'content' => 'required|string|max:255',
        'plant_id' => 'required|exists:plants,id',
        'anonymous' => 'nullable|boolean',
    ]);

    Advice::create([
        'content' => $request->content,
        'plant_id' => $request->plant_id,
        'user_id' => $request->filled('anonymous') ? null : Auth::id(),
    ]);

    return redirect()->back()->with('success', 'Advice posted successfully!');
}



    public function index()
    {
        $advices = Advice::with('user', 'plant')->get(); 
        return view('backoffice.advices.index', compact('advices')); 
    }

    public function edit($id)
{
    $advice = Advice::findOrFail($id);
    $plants = Plant::all(); 
    return view('backoffice.advices.edit', compact('advice', 'plants'));
}


    
public function update(Request $request, $id)
{
    $request->validate([
        'content' => 'required|string|max:255',
        'plant_id' => 'required|exists:plants,id', // Validate that plant_id is present and valid
    ]);

    $advice = Advice::findOrFail($id);
    $advice->content = $request->content;
    $advice->plant_id = $request->plant_id; // Update the plant_id
    $advice->save();

    return redirect()->route('admin.advices.index')->with('success', 'Advice updated successfully!');
}


    // Remove the specified advice
    public function destroy($id)
    {
        $advice = Advice::findOrFail($id);
        $advice->delete();

        return redirect()->route('admin.advices.index')->with('success', 'Advice deleted successfully!');
    }
}
