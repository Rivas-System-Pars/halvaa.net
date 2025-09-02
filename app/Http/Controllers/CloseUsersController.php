<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CloseUsersController extends Controller
{
    public function create(Request $request)
    {
        $auth = auth()->user();
        $type = $request->query('type', 'followers');
    
        if ($type === 'close') {
            // خروجی: User ها (pivot: close_users)
            $list = $auth->closeUsers()
                ->with('profileImage')
                ->orderBy('close_users.created_at', 'desc')
                ->paginate(20)
                ->withQueryString();
        } else {
            // خروجی: Follow ها (HasMany) + حذف کسانی که در close_users هستند
            $list = $auth->followers()
                ->whereNotExists(function ($q) use ($auth) {
                    $q->select(DB::raw(1))
                      ->from('close_users')
                      ->whereColumn('close_users.close_user_id', 'follows.follower_id')
                      ->where('close_users.owner_id', $auth->id);
                })
                ->with(['follower.profileImage'])   // Follow::follower -> User
                ->orderBy('created_at', 'desc')     // created_at خود رکورد follow
                ->paginate(20)
                ->withQueryString();
        }
    
        return view('back.users.closeusers.create', [
            'list' => $list,
            'type' => $type,
        ]);
    }

    // افزودن یک کاربر به لیست کلوز
    public function store(Request $request)
    {
        $request->validate([
            'close_user_id' => ['required', 'exists:users,id', 'different:auth_id'],
        ], [], [
            'close_user_id' => 'کاربر',
        ]);

        $auth = auth()->user();
        $closeUserId = (int) $request->close_user_id;

        $isFollower = Follow::where('following_id', $auth->id)
            ->where('follower_id', $closeUserId)
            ->where('status', 'accepted') // یا 1
            ->exists();


        $auth->closeUsers()->syncWithoutDetaching([$closeUserId]);

        return back()->with('success', 'به لیست کلوز اضافه شد.');
    }

    public function destroy(User $user)
    {
        $auth = auth()->user();
        $auth->closeUsers()->detach($user->id);
        return back()->with('success', 'از لیست کلوز حذف شد.');
    }
}
