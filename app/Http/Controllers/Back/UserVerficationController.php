<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\UserVerfication;
use Illuminate\Http\Request;

class UserVerficationController extends Controller
{
    /**
     * نمایش فرم ارسال مدارک (یا پیام خرید پکیج)
     */
    public function create()
    {
        $user = auth()->user();

        // چک می‌کنیم کاربر حداقل یک سفارش با status=paid
        // و محصول دسته‌بندی 100 داشته باشد
        $hasAccess = Order::where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereHas('products', function($q) {
                $q->where('category_id', 100);
            })
            ->exists();

		    $verifications = $user->verification()->latest()->get();

	
        return view('back.users.verfication.create', compact('user', 'hasAccess' , 'verifications'));
    }

    /**
     * دریافت و ذخیره مدارک
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // مجدداً دسترسی را بررسی می‌کنیم
        $hasAccess = Order::where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereHas('products', function($q) {
                $q->where('category_id', 100);
            })
            ->exists();

        if (! $hasAccess) {
            return redirect()->route('back.users.verfication.create')
                ->with('error', 'برای ارسال مدارک ابتدا پکیج مورد نظر را تهیه کنید.');
        }


        $existingApproved = UserVerfication::where('user_id', $user->id)
    ->where('status', 'approved')
    ->first();

if ($existingApproved) {
    return redirect()->back()
        ->with('error', 'درخواست شما قبلاً تأیید شده و امکان ارسال مجدد وجود ندارد.')
        ->with('status', $existingApproved->status)
        ->with('admin_note', $existingApproved->admin_note);
}

        // اعتبارسنجی
        $request->validate([
            'national_card'      => 'required|image|max:2048',
            'birth_certificate'  => 'required|image|max:2048',
            'death_cerification' => 'required|image|max:2048',
        ]);

        // مسیر ذخیره
        $folder = "uploads/verification/{$user->id}";
        $destinationPath = public_path($folder);
        if (! file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // ذخیره فایل‌ها
        $files = [
            'national_card'     => $request->file('national_card'),
            'birth_certificate' => $request->file('birth_certificate'),
            'death_cerification'=> $request->file('death_cerification'),
        ];

        foreach ($files as $field => $file) {
            $name = "{$field}." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $name);
            $data[$field] = "/{$folder}/{$name}";
        }

        // ایجاد رکورد
        UserVerfication::create(array_merge($data, [
            'user_id' => $user->id,
            'status'  => 'pending',
        ]));

        return redirect()->back()
            ->with('success', 'مدارک شما با موفقیت ارسال شد و در انتظار بررسی می‌باشد.');
    }
}
