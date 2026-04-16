<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Experience::orderBy('order')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'date' => 'required',
            'order' => 'nullable',
        ]);

        Experience::create($data);

        return response()->json([
            'message' => 'Experience created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Experience::findOrFail($id);

        return response()->json([
            'data' => $data,
            'message' => 'Experience fetched successfully',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $experience = Experience::findOrFail($id);
        $data = $request->validate([
            'title' => 'required',
            'date' => 'required',
            'order' => 'nullable',
        ]);

        $experience->update($data);

        return response()->json([
            'message' => 'Experience updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $experience = Experience::findOrFail($id);

        $experience->delete();

        return response()->json([
            'message' => 'Experience deleted successfully',
        ], 200);
    }
}
