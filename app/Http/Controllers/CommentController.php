<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    //get all comment

    public function index($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response()->json([
                'message' => 'Post Not Found'
            ], 403); 
        }

        return response()->json([
            'comments' => $post->comments()->with('user:id,username,image')
            ->get()
        ], 200); 
    }

    //create a comment
    public function store(Request $request,$id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response()->json([
                'message' => 'Post Not Found'
            ], 403); 
        }

        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);

        Comment::create([
            'comment' => $attrs['comment'],
            'post_id' => $id,
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
           'message' => 'Comment Created'
        ], 200); 
    }

    //update a comment
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response()->json([
                'message' => 'Comment not found'
             ], 403); 
        }

        if($comment->user_id != auth()->user()->id)
        {
            return response()->json([
                'message' => 'Permission denied'
             ], 403); 
        }

        $attrs = $request->validate([
            'comment' => 'required|string'
        ]);

        $comment->update([
            'comment' => $attrs['comment']
        ]);

        return response()->json([
            'message' => 'Comment updated'
         ], 200); 
    }

    //delete a comment
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if(!$comment)
        {
            return response()->json([
                'message' => 'Comment not found'
             ], 403); 
        }

        if($comment->user_id != auth()->user()->id)
        {
            return response()->json([
                'message' => 'Permission denied'
             ], 403); 
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment Deleted'
         ], 200); 
    }
}
