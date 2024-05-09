<?php

namespace App\Http\Controllers;

use App\Models\Post; // Pastikan untuk mengimpor model Post jika belum dilakukan
use Illuminate\Http\Request;

class PostWebController extends Controller
{
    public function show()
    {
        $posts = Post::orderBy('created_at', 'desc')
                    ->with('user:id,username,image')
                    ->withCount('comments', 'likes')
                    ->with(['likes' => function($like) {
                        $like->where('user_id', auth()->id())
                            ->select('id', 'user_id', 'post_id');
                    }])
                    ->get(); 

        return view('post', [
            'posts' => $posts,
        ]);
    }
}
