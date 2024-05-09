<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    // Mendapatkan semua postingan
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')
                    ->with('user:id,username,image')
                    ->withCount('comments', 'likes')
                    ->with('likes', function($like){
                        return $like->where('user_id', auth()->user()->id)
                        ->select('id', 'user_id', 'post_id')->get();
                    })
                    ->get(); 

        return response()->json([
            'posts' => $posts
        ], 200); 
    }

    public function show($id)
    {
        $post = Post::find($id)
        ->withCount('comments', 'likes')
        ->get();

        return response()->json([
            'post' => $post
        ], 200); 
    }

    //create a post
    public function store(Request $request)
    {
        $attrs = $request->validate([
            'body' => 'required|string',
        ]);

        $image = $this->saveImage($request->image, 'posts');

        $post = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id,
            'image' => $image
        ]);

        return response()->json([
            'message' => 'Post Created',
            'post' => $post
        ], 200); 
    }

    //update a post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if(!$post)
        {
            return response([
                'message' => 'Post Not Found'
            ], 403);
        }

        if($post->user_id != auth()->user()->id)
        {
            return response()->json([
                'message' => 'Permission Denied',
            ], 403); 
        }

        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $post->update([
            'body' => $attrs['body']
        ]);

        return response()->json([
            'message' => 'Post Created',
            'post' => $post
        ], 200); 
    }

    //delete
    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post Not Found'
            ], 404);
        }

        if($post->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response()->json([
            'message' => 'Post Deleted',
        ], 200); 
    }
}
