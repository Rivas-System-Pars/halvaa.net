<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Relatives;
use App\Models\RelativesTypes;
use App\Models\User;
use Illuminate\Validation\Rule;
use Auth;
use Illuminate\Http\Request;


class RelativesController extends Controller
{
    public function index()
    {
        $relatives = Relatives::with(['user', 'selectedUser'])
            ->where('user_id', auth()->id()) // 👈 فقط رکوردهای کاربر لاگین
            ->orderByDesc('id')
            ->paginate(10);

        return view('back.relatives.index', compact('relatives'));
    }

    public function create()
    {
        $me = auth()->id();

        $users = User::query()
            ->when($me, fn($q) => $q->where('id', '!=', $me)) // خودِ لاگین‌شده تو لیست نباشه
            ->orderByDesc('id')
            ->take(100)
            ->get();
        $relationTypes = RelativesTypes::query()
            ->where('is_active', true)
            ->orderBy('ordering')
            ->orderBy('id')
            ->get(['id', 'title']);
        // dd($relationTypes);

        return view('back.relatives.create', compact('users', 'relationTypes'));
    }



    public function store(Request $request)
    {
        $titles = RelativesTypes::where('is_active', true)->pluck('title')->toArray();

        $data = $request->validate([
            'option_value' => 'required|integer|exists:users,id',
            'option_name' => ['required', 'string', Rule::in($titles)],
        ]);

        if ((int) $data['option_value'] === (int) auth()->id()) {
            return back()->withErrors(['option_value' => 'نمی‌توانید خودتان را انتخاب کنید.'])->withInput();
        }

        // ✳️ چک تکراری بودن همین کاربر انتخاب‌شده برای همین یوزر لاگین
        $alreadyExists = Relatives::where('user_id', auth()->id())
            ->where('option_value', (int) $data['option_value'])
            ->exists();

        if ($alreadyExists) {
            return back()->withErrors([
                'option_value' => 'این کاربر قبلاً به‌عنوان وابسته برای شما ثبت شده است.'
            ])->withInput();
        }
        Relatives::create([
            'user_id' => auth()->id(),
            'option_value' => (int) $data['option_value'],
            'option_name' => $data['option_name'], // همون رشته انتخاب‌شده
        ]);

        return redirect()->route('admin.relatives.index')->with('success', 'ثبت شد.');
    }

    public function destroy(Relatives $relative)
    {
        // (اختیاری) محدودیت: فقط صاحب رکورد یا کسی که دسترسی دارد
        if ($relative->user_id !== auth()->id() && !auth()->user()->can('relatives.delete')) {
            abort(403);
        }

        $relative->delete();



        return back()->with('success', 'وابسته حذف شد.');
    }

    public function edit(Relatives $relative)
    {
        // (اختیاری) فقط صاحب رکورد/ادمین اجازه داشته باشه
        if ($relative->user_id !== auth()->id() && !auth()->user()->can('relatives.update')) {
            abort(403);
        }

        // لیست کاربران برای option_value (خودِ لاگین را حذف کن)
        $me = auth()->id();
        $users = User::query()
            ->when($me, fn($q) => $q->where('id', '!=', $me))
            ->orderByDesc('id')
            ->take(100)
            ->get();

        // عناوین نسبت‌ها
        $relationTypes = RelativesTypes::where('is_active', true)
            ->orderBy('ordering')->orderBy('id')
            ->get(['id', 'title']);

        return view('back.relatives.edit', compact('relative', 'users', 'relationTypes'));
    }
    public function update(Request $request, Relatives $relative)
    {
        if ($relative->user_id !== auth()->id() && !auth()->user()->can('relatives.update')) {
            abort(403);
        }

        $titles = RelativesTypes::where('is_active', true)->pluck('title')->toArray();

        $data = $request->validate([
            'option_value' => ['required', 'integer', 'exists:users,id'],
            'option_name' => ['required', 'string', Rule::in($titles)],
        ]);

        // جلوگیری از انتخاب خود کاربر
        if ((int) $data['option_value'] === (int) auth()->id()) {
            return back()->withErrors(['option_value' => 'نمی‌توانید خودتان را انتخاب کنید.'])->withInput();
        }

        // جلوگیری از تکرار همین کاربرِ انتخاب‌شده در بین ردیف‌های دیگرِ همین user
        $exists = Relatives::where('user_id', auth()->id())
            ->where('option_value', (int) $data['option_value'])
            ->where('id', '!=', $relative->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['option_value' => 'این کاربر قبلاً برای شما ثبت شده است.'])->withInput();
        }

        $relative->update([
            'option_value' => (int) $data['option_value'],
            'option_name' => $data['option_name'],
        ]);

        return redirect()->route('admin.relatives.index')->with('success', 'ویرایش انجام شد.');
    }
}



