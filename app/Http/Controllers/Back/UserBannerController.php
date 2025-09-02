<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserBannerController extends Controller
{
    public function create()
    {
        $userId = auth()->id();

        // دریافت همه بنرهای کاربر جاری
        $banners = \App\Models\UserBanner::where('user_id', $userId)
            ->latest()
            ->get();

        return view('back.users.userbanner.create', compact('banners'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        $userId = $user->id;

        // مسیر ذخیره‌سازی فایل
        $folderPath = public_path("uploads/userbanner/{$userId}");

        // اگر پوشه وجود نداشت، بسازش
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // دریافت فایل و ساخت نام منحصربه‌فرد
        $image = $request->file('image');
        $filename = time() . '_' . $image->getClientOriginalName();

        // انتقال فایل
        $image->move($folderPath, $filename);

        // ذخیره در دیتابیس
        \App\Models\UserBanner::create([
            'user_id' => $userId,
            'path' => "uploads/userbanner/{$userId}/{$filename}", // ذخیره مسیر نسبی
        ]);

        return redirect()->back()->with('success', 'بنر با موفقیت آپلود شد.');
    }

    public function destroy($id)
    {
        $banner = \App\Models\UserBanner::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // حذف فایل از پوشه
        if (file_exists(public_path($banner->path))) {
            unlink(public_path($banner->path));
        }

        // حذف رکورد از دیتابیس
        $banner->delete();

        return back()->with('success', 'بنر با موفقیت حذف شد.');
    }


}
