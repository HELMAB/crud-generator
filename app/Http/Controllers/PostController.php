<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();

        return response()->json($posts);
    }

    public function store(PostRequest $request)
    {
        $post = Post::create($request->all());

        return response()->json($post, 201);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        return response()->json($post);
    }

    public function update(PostRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());

        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        Post::destroy($id);

        return response()->json(null, 204);
    }
}
