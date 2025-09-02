<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostLikeController extends Controller
{

    public function toggle(Request $request)
    {
        $userId = auth()->id();
        $postId = $request->post_id;

        // Check if the like already exists
        $existingLike = PostLike::where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $status = 'unliked';
        } else {
            // Manually create the like with user_id and post_id
            PostLike::create([
                'user_id' => $userId,
                'post_id' => $postId,
            ]);
            $status = 'liked';
        }

        // Return likes count for this post
        $likesCount = PostLike::where('post_id', $postId)->count();

        return response()->json([
            'status' => $status,
            'likes_count' => $likesCount,
        ]);
    }

    public function likeExist(Request $request, $userPost)
    {
        $user_login = Auth::check() == 'true' ? auth()->user() : 0;
		$like_count = DB::table('post_likes')->where('post_id', $userPost)
            ->count();
		if($user_login === 0)
		{
			return response()->json([
            'like_exist'    =>    0,
            'like_count'    =>    $like_count
        ]);
		}
        $like_exist = DB::table('post_likes')->where('user_id', $user_login->id)
            ->where('post_id', $userPost)
            ->first();
        

        return response()->json([
            'like_exist'    =>  $like_exist ? True : False,
            'like_count'    =>    $like_count
        ]);
    }
}