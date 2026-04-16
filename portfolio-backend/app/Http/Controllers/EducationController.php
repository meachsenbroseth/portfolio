<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Education::orderBy('order')->get());
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

        Education::create($data);

        return response()->json([
            'message' => 'Education created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $education= Education::findOrFail($id);

        return response()->json([
            'data' => $education,
            'message' => 'Education fetched successfully',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $education = Education::findOrFail($id);
        $data = $request->validate([
            'title' => 'required',
            'date' => 'required',
            'oder' => 'nullable',
        ]);

        $education->update($data);
        return response()->json([
            'message' => 'Education updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $education = Education::findOrFail($id);

        $education->delete();
        return response()->json([
            'message' => 'Education deleted successfully',
        ], 200);

    }
}
