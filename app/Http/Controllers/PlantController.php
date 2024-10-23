<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlantController extends Controller
{
    // Method to display a list of all plants (front)
    public function index()
    {
        $plants = Plant::all(); // Retrieve all plants
        return view('plants.index', compact('plants')); // Return the view with the plants
    }

    // Method to display a list of all plants (admin)
    public function indexAdmin()
    {
        $plants = Plant::all(); // Make sure this is the correct model
        return view('backoffice.plants.index', compact('plants')); // Ensure this matches your view path
    }

    // Method to show the form for creating a new plant (front)
    public function create()
    {
        $categories = Category::all(); // Get all categories
        return view('plants.create', compact('categories')); // Return the view to create a plant
    }

    // Method to show the form for creating a new plant (admin)
    public function createAdmin()
    {
        $categories = Category::all(); // Get all categories
        return view('backoffice.plants.create', compact('categories')); // Return the admin view to create a plant
    }

    // Method to store a newly created plant (front)
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'scientific_name' => 'required|string|min:3|max:255', // Added min length
            'common_name' => 'required|string|min:3|max:255', // Added min length
            'origin' => 'required|string|min:3|max:255', // Added min length
            'description' => 'nullable|string|min:10', // Added min length
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|max:2048' // Make image required
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('plants', 'public');
        }

        // Create the plant with the validated data
        Plant::create($data);

        // Redirect with success message
        return redirect()->route('plants.index')->with('success', 'Plante créée avec succès!');
    }

    // Method to store a newly created plant (admin)
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'scientific_name' => 'required|string|max:255',
            'common_name' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for image
        ]);

        // Handle the uploaded image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('plants', 'public'); // Store the image
        }

        // Create the plant
        Plant::create([
            'scientific_name' => $request->scientific_name,
            'common_name' => $request->common_name,
            'origin' => $request->origin,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image' => $imagePath, // Save the correct path
        ]);

        return redirect()->route('plants.index.admin')->with('success', 'Plant created successfully in admin dashboard!');
    }

    // Method to show the form for editing a plant (front)
    public function edit(Plant $plant)
    {
        $categories = Category::all(); // Get all categories
        return view('plants.edit', compact('plant', 'categories')); // Return the view to edit the plant
    }

    // Method to show the form for editing a plant (admin)
    public function editAdmin(Plant $plant)
    {
        $categories = Category::all(); // Get all categories
        return view('backoffice.plants.edit', compact('plant', 'categories')); // Return the admin view to edit the plant
    }

    // Method to update a plant (front)
    public function update(Request $request, Plant $plant)
    {
        // Validate the incoming request data
        $request->validate([
            'scientific_name' => 'required|string|max:255',
            'common_name' => 'required|string|max:255',
            'origin' => 'nullable|string|max:255',
            'description' => 'nullable|string|min:10', // Added min length
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048' // Validate image field
        ]);

        $data = $request->all();

        // Handle image upload if available
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($plant->image) {
                Storage::disk('public')->delete($plant->image);
            }
            // Store the new image
            $data['image'] = $request->file('image')->store('plants', 'public');
        }

        // Update the plant with the validated data
        $plant->update($data);

        // Redirect with success message
        return redirect()->route('plants.index')->with('success', 'Plante mise à jour avec succès!');
    }

    // Method to update a plant (admin)
    public function updateAdmin(Request $request, Plant $plant)
    {
        $request->validate([
            'scientific_name' => 'required|string|max:255',
            'common_name' => 'required|string|max:255',
            'origin' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image
        ]);

        // Handle the uploaded image
        $imagePath = $plant->image; // Keep the existing image path by default
        if ($request->hasFile('image')) {
            // Delete the old image if needed (optional)
            if ($plant->image) {
                Storage::disk('public')->delete($plant->image);
            }
            // Store the new image
            $imagePath = $request->file('image')->store('plants', 'public'); // Store the new image
        }

        $plant->update([
            'scientific_name' => $request->scientific_name,
            'common_name' => $request->common_name,
            'origin' => $request->origin,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image' => $imagePath, // Update the image path
        ]);

        return redirect()->route('plants.index.admin')->with('success', 'Plant updated successfully in admin dashboard!');
    }

    // Method to delete a plant (front)
    public function destroy(Plant $plant)
    {
        if ($plant->image) {
            Storage::disk('public')->delete($plant->image);
        }
        $plant->delete(); // Delete the plant

        return redirect()->route('plants.index')->with('success', 'Plant deleted successfully!'); // Redirect with success message
    }

    // Method to delete a plant (admin)
    public function destroyAdmin(Plant $plant)
    {
        if ($plant->image) {
            Storage::disk('public')->delete($plant->image);
        }
        $plant->delete(); // Delete the plant

        return redirect()->route('plants.index.admin')->with('success', 'Plant deleted successfully in admin dashboard!'); // Redirect with success message
    }
}