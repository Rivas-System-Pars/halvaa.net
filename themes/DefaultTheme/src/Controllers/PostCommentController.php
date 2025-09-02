<?php

namespace Themes\DefaultTheme\src\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Gallery;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\UserPost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostCommentController extends Controller
{

    public function store(Request $request, Post $post)
    {

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $user = auth()->user();

        $postId = (int) $request->route('userPost');
        $comment = PostComment::create([
            'user_id' => auth()->id(),
            'post_id' => $postId,
            'comment' => $validated['comment'],
        ]);
        return response()->json([
            'message' => 'Comment created successfully.',
            'data' => $comment,
            'username'    =>    $user->username ?? $user->first_name.' '.$user->last_name,
            'profile_image'    => $user->profileImage ? env('APP_URL') . '/' . $user->profileImage->image : null
        ], 201);
    }
}