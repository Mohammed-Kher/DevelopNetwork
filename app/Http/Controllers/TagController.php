<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use Validator;

class TagController extends Controller
{
    public function index() {
        $tags = Tag::all();

        return response([
            'tags' => $tags,
        ], 200);
    }

    public function store(Request $request) {
        
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:30|unique:tags',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $tag = Tag::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Tag created successfully',
            'name' => $tag->name,
        ], 200);
    }

    public function show(Tag $tag) {
        return response([
            'tag' => $tag,
        ], 200);
    }
    public function update(Request $request, Tag $tag) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:30|unique:tags',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $tag->name = $request->name;
        $tag->save();

        return response()->json([
            'message' => 'Tag updated successfully'
        ], 200);
    }

    public function destroy(Tag $tag) {
        if (!$tag) {
            return response()->json([
                'message' => 'Tag not found'
            ], 404);
        }

        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully'
        ], 200);
    }

}
