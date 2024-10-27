<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('tags')->orderBy('is_pinned', 'desc')->get();

        return response()->json(['posts' => $posts], 200);
    }

    /** 
     * if find at least one unavailable tag return false
     * @param array $tags
     * @return bool 
     */
    private function checkAvailableTags(array $tagIds): bool
    {
        $existingTags = Tag::whereIn('id', $tagIds)->count();

        return count($tagIds) === $existingTags;
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_pinned' => 'boolean',
            'tags' => 'required|array',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $areTagsAvailable = $this->checkAvailableTags($request->tags);

        if (!$areTagsAvailable) {
            return response()->json([
                'errors' => ['tags' => 'Some tags are not available.']
            ], 422);
        }

        $post = new Post($request->all());

        $extension = $request->file('cover_image')->extension();
        $uniqueFilename = uniqid() . '.' . $extension;

        $imagePath = $request->file('cover_image')->storeAs('public/post_images', $uniqueFilename);
        $post->cover_image = $imagePath;

        $post->save();

        if (isset($request['tags'])) {
            $post->tags()->attach($request['tags']);
        }

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ], 201);
    }
    public function show(Post $post)
    {
        $post->load('tags');
        return response()->json(['post' => $post], 200);
    }

    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_pinned' => 'boolean',
            'tags' => 'array',
        ]);

        $post->update($validatedData);

        if (isset($validatedData['tags'])) {
            $post->tags()->sync($validatedData['tags']);
        }

        return response()->json(['message' => 'Post updated successfully'], 200);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}