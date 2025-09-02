<?php

namespace Themes\DefaultTheme\src\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Models\UserPost;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPostController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;


        $posts = UserPost::with(['user', 'gallery'])
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->get();
        dd($posts);



        // اضافه کردن فیلد type به هر آیتم گالری
        $posts->each(function ($post) {
            $post->gallery->transform(function ($item) {
                $ext = pathinfo($item->image, PATHINFO_EXTENSION);
                $type = in_array(strtolower($ext), ['mp4', 'avi', 'webm', 'mov']) ? 'video' : 'image';
                $item->type = $type;
                return $item;
            });
        });

        return response()->json([
            'posts' => $posts
        ]);
    }



    public function store(Request $request)
    {

        $request->validate([
            'description' => 'nullable|string',
            //'images.*' => 'image|max:2048',
            //'videos.*' => 'mimes:mp4,avi,webm,mov|max:10240', // 10MB max size
            'images' => 'required|array',
            'is_private' => 'nullable|boolean',
            'is_pin' => 'nullable|boolean'

        ]);
        // Limit number of uploaded images
        if ($request->hasFile('images') && count($request->file('images')) > 10) {
            return response()->json([
                'message' => 'شما نمیتوانید بیشتر از 10 عکس اپلود کنید'
            ], 422);
        }

        // Limit number of uploaded videos
        if ($request->hasFile('videos') && count($request->file('videos')) > 3) {
            return response()->json([
                'message' => 'شما نمیتوانید بیشتر از 3 ویدیو اپلود کنید'
            ], 422);
        }

        $user = Auth::user();
        $user_id = $user->id;
        // dd($user_id);

        // Define upload folder path
        $folderPath = public_path("uploads/UserPosts/{$user_id}");

        // Create directory if it doesn't exist
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Create post
        $post = UserPost::create([
            'user_id' => $user_id,
            'description' => $request->description,
        ]);

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path("uploads/UserPosts/{$user_id}"), $filename);

                $post->gallery()->create([
                    'image' => "/uploads/UserPosts/{$user_id}/{$filename}",
                    'ordering' => $index,
                    'type' => 'image'
                ]);
            }
        }

        // Upload videos
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $index => $video) {
                $filename = uniqid() . '_' . time() . '.' . $video->getClientOriginalExtension();
                $video->move(public_path("uploads/UserPosts/{$user_id}"), $filename);

                $post->gallery()->create([
                    'image' => "/uploads/UserPosts/{$user_id}/{$filename}",
                    'ordering' => 100 + $index, // place videos after images
                    'type' => 'video'
                ]);
            }
        }

        return response()->json([
            'message' => 'پست با موفقیت ساخته شد',
            'post' => $post->load('gallery')
        ]);
    }


    public function show(UserPost $userPost)
    {
        // $this->authorize('view', $userPost);

        // بارگذاری تمام روابط مورد نیاز
        $userPost->load(['user', 'gallery', 'postComments', 'likes.user']);

        // تعداد لایک‌ها
        $likeCount = $userPost->likes->count();

        // لیست کاربران لایک‌کننده (مثلاً فقط username)
        $likeUsers = $userPost->likes->pluck('user.username');
        $commentUser = $userPost->postComments->pluck('user.username');


        return response()->json([
            'post' => $userPost,
            'like_count' => $likeCount,
            'like_users' => $likeUsers,
            'comment_user' => $commentUser
        ]);
    }



    public function destroy(UserPost $userPost)
    {
        $this->authorize('delete', $userPost);

        $userPost->gallery()->delete();
        $userPost->likes()->delete();
        $userPost->postComments()->delete();
        $userPost->delete();

        return response()->json([
            'message' => 'Post deleted successfully.'
        ]);
    }


    public function update(Request $request, UserPost $userPost)
    {
        $this->authorize('update', $userPost);

        $request->validate([
            'description' => 'nullable|string',
            //'images.*' => 'image|max:2048',
            //'videos.*' => 'mimes:mp4,avi,webm,mov|max:10240',
            'images' => 'required|array',
            'is_private' => 'nullable|boolean',
            'is_pin' => 'nullable|boolean'

        ]);

        if ($request->hasFile('images') && count($request->file('images')) > 10) {
            return response()->json([
                'message' => 'شما نمیتوانید بیشتر از 10 عکس اپلود کنید'
            ], 422);
        }

        $user = Auth::user();
        $user_id = $user->id;

        $userPost->update([
            'description' => $request->description,
        ]);

        foreach ($userPost->gallery as $media) {
            $path = public_path($media->image);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $userPost->gallery()->delete();

        $uploadPath = public_path("uploads/UserPosts/{$user_id}");
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // 🖼 آپلود عکس‌ها
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $filename);

                $userPost->gallery()->create([
                    'image' => "/uploads/UserPosts/{$user_id}/{$filename}",
                    'ordering' => $index,
                    'type' => 'image'
                ]);
            }
        }

        // 🎥 آپلود ویدیوها
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $index => $video) {
                $filename = uniqid() . '_' . time() . '.' . $video->getClientOriginalExtension();
                $video->move($uploadPath, $filename);

                $userPost->gallery()->create([
                    'image' => "/uploads/UserPosts/{$user_id}/{$filename}",
                    'ordering' => 100 + $index,
                    'type' => 'video'
                ]);
            }
        }

        return response()->json([
            'message' => 'پست با موفقیت ویرایش شد',
            'post' => $userPost->load('gallery')
        ]);
    }


    public function userPosts(Request $request, $userId)
    {
        $checkWriter = Auth::check();
        if ($checkWriter == 'true' && auth()->user()->id == $userId) {
            $checkWriter = 1;
        } else {
            $checkWriter = 0;
        }



        // واکشی پست‌های کاربر به همراه اطلاعات مربوط
        $posts = UserPost::with(['user.profileImage', 'gallery', 'postComments.user.profileImage', 'likes'])
            ->where('user_id', $userId)
            ->latest()
            ->get();
            // dd($posts->pluck('user_id'));
        $userId = auth()->id();
        $postId = $request->route('post');
        $ownerId = $posts ->pluck('user_id')->first();         // صاحب پروفایل/پست‌ها
        $viewerId = auth()->id();

        // Check if the like already exists
        $existingLike = PostLike::where('user_id', $userId)
            ->where('post_id', $postId)
            ->first();
        $likesCount = PostLike::where('post_id', $postId)->count();

        // تعیین نوع فایل در گالری
        $posts->each(function ($post) {
            $post->gallery->transform(function ($item) {
                $ext = pathinfo($item->image, PATHINFO_EXTENSION);
                $type = in_array(strtolower($ext), ['mp4', 'avi', 'webm', 'mov']) ? 'video' : 'image';
                $item->type = $type;
                return $item;
            });
        });

        // فقط صاحب پست یا اعضای کلوزِ او حق دیدن پست‌های خصوصی را دارند
        $canSeePrivate = $checkWriter === 1 || ($viewerId && DB::table('close_users')
                ->where('owner_id', $ownerId)
                ->where('close_user_id', $viewerId)
                ->exists());

        // لیست پست‌های خصوصی قابل نمایش برای این بیننده
        $private_posts = $canSeePrivate
            ? $posts->where('is_private', 1)->values()->all()
            : [];



        return response()->json([
            'posts' => $posts,
            'checkWriter' => $checkWriter,
            'private_posts' => $private_posts

        ]);
    }
}