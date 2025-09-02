<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\UserVerfication;
use Illuminate\Http\Request;

class VerificationController extends Controller
{

    public function index()
    {
        $verifications = UserVerfication::with('user')->latest()->get();
        return view('back.verification.index', compact('verifications'));
    }

    /**
     * نمایش جزئیات یک درخواست
     */
    public function show(UserVerfication $verification)
    {
        return view('back.verification.show', compact('verification'));
    }

    /**
     * عملیات تایید یا رد درخواست
     */
    public function update(Request $request, UserVerfication $verification)
    {
        $request->validate([
            'status'     => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $verification->status = $request->input('status');

        if ($request->input('status') === 'rejected') {
            // توضیح اجباری برای رد
            $request->validate(['admin_note' => 'required']);
            $verification->admin_note = $request->input('admin_note');
        } else {
            $verification->admin_note = null;
        }

        $verification->save();

    $verification->user->update([
        'is_verified' => $verification->status === 'approved' ? 1 : 0,
    ]);

        return redirect()
            ->route('admin.back.verification.index')
            ->with('success', 'وضعیت درخواست با موفقیت بروزرسانی شد.');
    }
}
