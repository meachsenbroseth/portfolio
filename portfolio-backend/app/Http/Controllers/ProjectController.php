<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Project::where('status', 'published')->latest('published_at')->paginate(10);

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'date' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'github_link' => 'nullable|string',
            'technologies' => 'nullable|json',
            'status' => 'nullable|in:published,draft',
        ]);

        $slug = Str::slug($data['title']);
        $count = Project::where('slug', 'LIKE', "{$slug}%")->count();

        $data['technologies'] = json_decode($request->technologies, true);

        $data['slug'] = $count ? "{$slug}-{$count}" : $slug;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $data['image'] = $path;
        }

        Project::create($data);

        return response()->json([
            'message' => 'Project created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        return response()->json([
            'data' => $project,
            'message' => 'Project fetched successfully',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'date' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'github_link' => 'nullable|string',
            'technologies' => 'nullable|json',
            'status' => 'nullable|in:published,draft',
        ]);

        $data['technologies'] = json_decode($request->technologies, true);

        if ($data['title'] !== $project->title) {
            $slug = Str::slug($data['title']);
            $count = Project::where('slug', 'LIKE', "{$slug}%")->count();
            $data['slug'] = $count ? "{$slug}-{$count}" : $slug;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $data['image'] = $path;
        }

        $project->update($data);

        return response()->json([
            'message' => 'Project updated successfully',
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project= Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'message'=>'Project deleted suessfully'
        ],200);
    }
}
