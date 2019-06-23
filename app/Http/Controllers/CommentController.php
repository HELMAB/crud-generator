<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::latest()->get();

        return response()->json($comments);
    }

    public function store(CommentRequest $request)
    {
        $comment = Comment::create($request->all());

        return response()->json($comment, 201);
    }

    public function show($id)
    {
        $comment = Comment::findOrFail($id);

        return response()->json($comment);
    }

    public function update(CommentRequest $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update($request->all());

        return response()->json($comment, 200);
    }

    public function destroy($id)
    {
        Comment::destroy($id);

        return response()->json(null, 204);
    }
}
