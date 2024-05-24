<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request, $postId) {
        $user = auth()->user(); // Mengambil user yang sedang login
    
        // Cek apakah postingan sudah ada di wishlist user
        $existingWishlist = Wishlist::where('user_id', $user->id)
                                      ->where('post_id', $postId)
                                      ->first();
    
        // Jika sudah ada, hapus dari wishlist
        if ($existingWishlist) {
            $existingWishlist->delete();
            return response()->json(['message' => 'Post removed from wishlist successfully']);
        }
    
        // Jika belum ada, tambahkan ke wishlist
        $wishlist = new Wishlist();
        $wishlist->user_id = $user->id;
        $wishlist->post_id = $postId;
        $wishlist->save();
    
        return response()->json(['message' => 'Post added to wishlist successfully']);
    }
    
    public function getWishlist() {
        $user = auth()->user(); // Mengambil user yang sedang login
    
        // Ambil daftar postingan yang ada di wishlist user
        $wishlist = Wishlist::where('user_id', $user->id)->with('post')->get();
    
        // Mengakses data postingan dari setiap item wishlist
        $data = $wishlist->map(function($item) {
            return $item->post;
        });
    
        return response()->json(['wishlist' => $data],200);
    }
    
    
    
}
