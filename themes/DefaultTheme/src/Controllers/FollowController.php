<?php

namespace themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class FollowController extends Controller
{

    public function follow(Request $request, User $userToFollow)
    {
        $user = Auth()->user();
        if ($user->id === $userToFollow->id) {
            return response()->json(['message' => 'شما قابل به انجام این کار نیستید !'], 400);
        }

        $isPrivate = $userToFollow->is_private;

        $follow = Follow::updateOrCreate([
            'follower_id' => $user->id,
            'following_id' => $userToFollow->id
        ], [
            'status' => $isPrivate ? 'درحال پردازش' : 'قبول شده'
        ]);

        $message = $isPrivate ? 'درخواست دنبال کردن ارسال شد' : 'با موفقیت دنبال شد';

        return back()->with('success', $message);
    }




    public function respondToRequest(Request $request, $followerId)
    {
        $user = auth()->user();

        $request->validate([
            'status' => 'required|in:قبول شده,رد شده',
        ]);

        $follow = Follow::where('follower_id', (int) $followerId)
            ->where('following_id', $user->id)
            ->first();

        if (!$follow) {
            return redirect()->back()->withErrors(['message' => 'درخواستی یافت نشد']);
        }

        $follow->status = $request->status;
        $follow->save();


        return redirect()->back()->with([
            'follow' => $follow,
            'status_message' => $request->status,
        ]);
    }


    public function followRequests()
    {
        $user = auth()->user();

        $requests = Follow::with('follower')
            ->where('following_id', $user->id)
            ->where('status', 'درحال پردازش')
            ->latest()
            ->paginate(10);

        // آیدی افرادی که این کاربر فالو کرده
        $followedUserIds = Follow::where('follower_id', $user->id)
            ->where('status', 'قبول شده')
            ->pluck('following_id');

        // فقط یوزرهایی که پیام دارند (یعنی حداقل یک پیام نوشته‌اند)
        $followedUsersWithMessages = User::whereIn('id', $followedUserIds)
            ->whereHas('messages') // شرط وجود پیام
            ->with([
                'messages' => function ($q) {
                    $q->latest()->take(10); 
                }
            ])
            ->get();

        return view('front::user.notification.notifications', compact('requests', 'followedUsersWithMessages'));
    }


    public function unfollow(User $userToUnfollow)
    {
        $user = auth()->user();

        // بررسی اینکه دنبال کرده بود یا نه
        $follow = Follow::where('follower_id', $user->id)
            ->where('following_id', $userToUnfollow->id)
            ->first();

        if (!$follow) {
            return back()->withErrors(['message' => 'شما این کاربر را دنبال نمی‌کنید.']);
        }

        // حذف رابطه دنبال کردن
        $follow->delete();

        return back()->with('success', 'شما با موفقیت آنفالو کردید.');
    }



}
