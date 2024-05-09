<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    public function likeOrUnlike($id)
    {
        $post = Post::find($id);
        if(!$post)
        {
            return response()->json([
                'message' => 'Post Not Foud',
            ], 403);
        }

        $like = $post->likes()->where('user_id', auth()->user()->id)->first();

        if(!$like)
        {
            Like::create([
                'post_id' => $id,
                'user_id' => auth()->user()->id
            ]);

            return response()->json([
                'message' => 'Liked',
            ], 200);
        }
        $like->delete();

        return response()->json([
            'message' => 'Disliked',
        ], 200);

    }
}
