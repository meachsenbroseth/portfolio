<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Blog::where('status', 'published')
            ->latest('published_at')
            ->paginate(10);

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'nullable|in:draft,published',
            'published_at' => 'nullable',
        ]);

        $slug = Str::slug($data['title']);
        $count = Blog::where('slug', 'LIKE', "{$slug}%")->count();

        $data['slug'] = $count ? "{$slug}-{$count}" : $slug;

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('posts', 'public');
            $data['cover_image'] = url($path);
        }

        if (($data['status'] ?? null) === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Blog::create($data);

        return response()->json([
            'message' => 'Blog created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        return response()->json([
            'data' => $blog,
            'message' => 'Blog fetched successfully',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $blog = Blog::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|string',
            'status' => 'nullable|in:draft,published',
            'published_at' => 'nullable',
        ]);

        if ($data['title'] !== $blog->title) {
            $slug = Str::slug($data['title']);
            $count = Blog::where('slug', 'LIKE', "{$slug}%")->count();
            $data['slug'] = $count ? "{$slug}-{$count}" : $slug;
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('blogs', 'public');
            $data['cover_image'] = $path;
        }

        if (($data['status'] ?? null) === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        return response()->json([
            'message' => 'Blog updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);

        $blog->delete();

        return response()->json([
            'message' => 'Blog deleted successfully',
        ], 200);
    }
}
