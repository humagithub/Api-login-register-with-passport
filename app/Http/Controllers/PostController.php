<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /api/posts
    public function index()
    {
        return response()->json(Post::with('user')->get());
    }

    // POST /api/posts
    public function store(Request $request)
    {
    dd('ok');
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(), // Assign authenticated user
        ]);

        return response()->json($post, 201);
    }

    // GET /api/posts/{post}
    public function show(Post $post)
    {
        return response()->json($post);
    }

    // PUT /api/posts/{post}
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->update($request->only('title', 'content'));

        return response()->json($post);
    }

    // DELETE /api/posts/{post}
    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}

