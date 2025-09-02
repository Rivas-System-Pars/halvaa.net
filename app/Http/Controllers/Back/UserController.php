<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Relics;
use App\Models\User;
use App\Models\UserLifeBio;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Exports\UsersExport;
use App\Http\Resources\Datatable\User\UserCollection;
use Illuminate\Support\Facades\File;
use App\Models\Role;
use App\Models\UserAnnouncement;
use App\Models\UserLifeCalender;
use App\Models\UserMemorial;
use App\Models\UserMessage;
use App\Models\UserNotice;
use App\Models\UserWill;
use App\Rules\NotSpecialChar;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {
        return view('back.users.index');
    }

    public function apiIndex(Request $request)
    {
        $this->authorize('users.index');

        $users = User::filter($request);

        $users = datatable($request, $users);

        return new UserCollection($users);
    }

    public function create()
    {
        $roles = Role::latest()->get();
        $categories = Category::query()->select(['id', 'title'])->get();
        $products = Product::query()->select(['id', 'title'])->get();

        return view('back.users.create', compact('roles', 'categories', 'products'));
    }

    public function edit(User $user)
    {
        $roles = Role::latest()->get();
        $categories = Category::query()->select(['id', 'title'])->get();
        $products = Product::query()->select(['id', 'title'])->get();

        return view('back.users.edit', compact('user', 'roles', 'categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->merge(['username' => $request->filled('username') ? "0" . substr($request->username, -10) : null]);
        $rules = [
            'first_name' => ['required', 'string', 'max:255', new NotSpecialChar()],
            'last_name' => ['required', 'string', 'max:255', new NotSpecialChar()],
            'level' => 'in:user,admin',
            'username' => ['required', 'string', 'regex:/^(?:98|\+98|0098|0)?9[0-9]{9}$/', 'unique:users'],
            'email' => ['string', 'email', 'max:255', 'unique:users', 'nullable'],
            'password' => ['required', 'string', 'confirmed:confirmed'],
            'referral_code' => ['nullable', 'string', 'unique:users,referral_code'],
            'referral_percentage' => ['nullable', 'numeric', 'between:0,100'],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id'
        ];
        if (auth()->user()->can('marketer')) {
            $rules['referral_code'] = ['nullable', 'string', 'unique:users,referral_code'];
            $rules['referral_percentage'] = ['nullable', 'numeric', 'between:0,100'];
            $rules['referral_categories'] = ['nullable', 'array'];
            $rules['referral_categories.*'] = ['required', 'array:value,title'];
            $rules['referral_categories.*.value'] = ['required', 'numeric', 'between:1,100'];
            $rules['referral_categories.*.title'] = ['required', 'exists:categories,title'];
            $rules['referral_products'] = ['nullable', 'array'];
            $rules['referral_products.*'] = ['required', 'array:value,title'];
            $rules['referral_products.*.value'] = ['required', 'numeric', 'between:1,100'];
            $rules['referral_products.*.title'] = ['required', 'exists:products,title'];
        }
        $this->validate($request, $rules, [], array_merge(collect($request->referral_categories)->mapWithKeys(function ($item, $key) {
            return [
                "referral_categories." . $key => optional($item)->offsetGet('title'),
                "referral_categories." . $key . ".value" => "مقدار",
                "referral_categories." . $key . ".title" => "عنوان",
            ];
        })->toArray(), collect($request->referral_products)->mapWithKeys(function ($item, $key) {
            return [
                "referral_products." . $key => optional($item)->offsetGet('title'),
                "referral_products." . $key . ".value" => "مقدار",
                "referral_products." . $key . ".title" => "عنوان",
            ];
        })->toArray()));
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'level' => 'admin',
            'password' => Hash::make($request->password),
            'verified_at' => $request->verified_at ? Carbon::now() : null,
        ];

        // dd($data);
        if (auth()->user()->can('marketer')) {
            $data['referral_code'] = $request->referral_code;
            $data['referral_percentage'] = $request->filled('referral_percentage') ? $request->referral_percentage : 0;
        }
        $user = User::create($data);

        if ($request->filled('referral_categories')) {
            $user->referralCategories()->sync(collect($request->referral_categories)->filter(function ($item, $key) {
                return array_key_exists('value', $item) && strlen(trim($item['value']));
            })->mapWithKeys(function ($item, $key) {
                return [$key => ['percentage' => $item['value']]];
            })->toArray());
        } else {
            $user->referralCategories()->sync([]);
        }
        if ($request->filled('referral_products')) {
            $user->referralProducts()->sync(collect($request->referral_products)->filter(function ($item, $key) {
                return array_key_exists('value', $item) && strlen(trim($item['value']));
            })->mapWithKeys(function ($item, $key) {
                return [$key => ['percentage' => $item['value']]];
            })->toArray());
        } else {
            $user->referralProducts()->sync([]);
        }

        if ($request->hasFile('image')) {
            $file = $request->image;
            $name = uniqid() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $request->image->storeAs('users', $name);

            $user->image = '/uploads/users/' . $name;
            $user->save();
        }

        $user->roles()->attach($request->roles);

        toastr()->success('کاربر با موفقیت ایجاد شد.');

        return response('success');
    }

    public function update(User $user, Request $request)
    {
        $request->merge(['username' => $request->filled('username') ? "0" . substr($request->username, -10) : null]);
        $rules = [
            'first_name' => ['required', 'string', 'max:255', new NotSpecialChar()],
            'last_name' => ['required', 'string', 'max:255', new NotSpecialChar()],
            'level' => 'in:user,admin',
            'username' => ['required', 'string', 'regex:/^(?:98|\+98|0098|0)?9[0-9]{9}$/', "unique:users,username,$user->id"],
            'email' => ['string', 'email', 'max:255', "unique:users,email,$user->id", 'nullable'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed:confirmed'],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id'
        ];
        if (auth()->user()->can('marketer')) {
            $rules['referral_code'] = ['nullable', 'string', 'unique:users,referral_code,' . $user->id];
            $rules['referral_percentage'] = ['nullable', 'numeric', 'between:0,100'];
            $rules['referral_categories'] = ['nullable', 'array'];
            $rules['referral_categories.*'] = ['required', 'array:value,title'];
            $rules['referral_categories.*.value'] = ['required', 'numeric', 'between:1,100'];
            $rules['referral_categories.*.title'] = ['required', 'exists:categories,title'];
            $rules['referral_products'] = ['nullable', 'array'];
            $rules['referral_products.*'] = ['required', 'array:value,title'];
            $rules['referral_products.*.value'] = ['required', 'numeric', 'between:1,100'];
            $rules['referral_products.*.title'] = ['required', 'exists:products,title'];
        }
        $this->validate($request, $rules, [], array_merge(collect($request->referral_categories)->mapWithKeys(function ($item, $key) {
            return [
                "referral_categories." . $key => optional($item)->offsetGet('title'),
                "referral_categories." . $key . ".value" => "مقدار",
                "referral_categories." . $key . ".title" => "عنوان",
            ];
        })->toArray(), collect($request->referral_products)->mapWithKeys(function ($item, $key) {
            return [
                "referral_products." . $key => optional($item)->offsetGet('title'),
                "referral_products." . $key . ".value" => "مقدار",
                "referral_products." . $key . ".title" => "عنوان",
            ];
        })->toArray()));

        $verified_at = $user->verified_at ?: Carbon::now();

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'level' => $request->level,
            'verified_at' => $request->verified_at ? $verified_at : null,
        ];
        if (auth()->user()->can('marketer')) {
            $data['referral_code'] = $request->referral_code;
            $data['referral_percentage'] = $request->filled('referral_percentage') ? $request->referral_percentage : 0;
        }
        $user->update($data);

        if ($request->filled('referral_categories')) {
            $user->referralCategories()->sync(collect($request->referral_categories)->filter(function ($item, $key) {
                return array_key_exists('value', $item) && strlen(trim($item['value']));
            })->mapWithKeys(function ($item, $key) {
                return [$key => ['percentage' => $item['value']]];
            })->toArray());
        } else {
            $user->referralCategories()->sync([]);
        }
        if ($request->filled('referral_products')) {
            $user->referralProducts()->sync(collect($request->referral_products)->filter(function ($item, $key) {
                return array_key_exists('value', $item) && strlen(trim($item['value']));
            })->mapWithKeys(function ($item, $key) {
                return [$key => ['percentage' => $item['value']]];
            })->toArray());
        } else {
            $user->referralProducts()->sync([]);
        }

        if ($request->password) {
            $password = Hash::make($request->password);

            $user->update([
                'password' => $password
            ]);

            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        if ($request->hasFile('image')) {
            $file = $request->image;
            $name = uniqid() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $request->image->storeAs('users', $name);

            $user->image = '/uploads/users/' . $name;
            $user->save();
        }

        $user->roles()->sync($request->roles);

        toastr()->success('کاربر با موفقیت ویرایش شد.');

        return response('success');
    }

    public function show(User $user)
    {
        return view('back.users.show', compact('user'));
    }

    public function destroy(User $user, $multiple = false)
    {
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        if (!$multiple) {
            toastr()->success('کاربر با موفقیت حذف شد.');
        }

        return response('success');
    }

    public function multipleDestroy(Request $request)
    {
        $this->authorize('users.delete');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => [
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('id', '!=', auth()->user()->id)->where('level', '!=', 'creator');
                })
            ]
        ]);

        foreach ($request->ids as $id) {
            $user = User::find($id);
            $this->destroy($user, true);
        }

        return response('success');
    }

    public function export(Request $request)
    {
        $this->authorize('users.export');

        $users = User::where('level', '!=', 'creator')
            ->filter($request)
            ->get();

        switch ($request->export_type) {
            case 'excel': {
                return $this->exportExcel($users, $request);
                break;
            }
            default: {
                return $this->exportPrint($users, $request);
            }
        }
    }

    public function views(User $user)
    {
        $views = $user->views()->latest()->paginate(20);

        return view('back.users.views', compact('views', 'user'));
    }

    public function showProfile()
    {
        return view('back.users.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $this->validate($request, [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'username' => 'required|string|max:191',
        ]);

        if ($request->password || $request->password_confirmation) {
            $this->validate($request, [
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required',
            ]);

            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            $this->validate($request, [
                'image' => 'image|max:2048',
            ]);

            $imageName = time() . '_' . $user->id . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('uploads/users/'), $imageName);

            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            $user->image = '/uploads/users/' . $imageName;
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->bio = $request->bio;
        $user->save();

        if ($request->password) {
            DB::table('sessions')->where('user_id', auth()->user()->id)->delete();
        }


        $options = $request->only([
            'theme_color',
            'theme_font',
            'menu_type'
        ]);

        foreach ($options as $option => $value) {
            user_option_update($option, $value);
        }

        return response()->json('success');
    }

    private function exportExcel($users, Request $request)
    {
        return Excel::download(new UsersExport($users, $request), 'users.xlsx');
    }

    private function exportPrint($users, Request $request)
    {
        //
    }

    public function UserInfoShow(Request $request, $user_id)
    {
        // dd($user_id);
        $user = User::with('familyTree')->findOrFail($user_id);

        // If you added a url() helper on Gallery, you can use it. Otherwise build an asset URL safely.
        $familyTree = $user->familyTree; // MorphOne → one record or null
		$familyTree_obj[0] = $familyTree;
        // dd($familyTree);
        $response = [
            'UserBioLife' => UserLifeBio::where('user_id', $user_id)->get() ?? null,
            'UserLifeCalender' => UserLifeCalender::where('user_id', $user_id)->get() ?? null,
            'UserWill' => UserWill::where('user_id', $user_id)->get() ?? null,
            'UserAnnouncement' => UserAnnouncement::where('user_id', $user_id)->get() ?? null,
            'UserNotice' => UserNotice::where('user_id', $user_id)->get() ?? null,
            'UserMessage' => UserMessage::where('user_id', $user_id)->get() ?? null,
            'family_tree' => $familyTree ? $familyTree_obj : null,

            'relics' => Relics::where('user_id', $user_id)->get() ?? null,
        ];

        return response($response);
    }


    /*
                        User Info
    */

    public function UserInfoLifeBiographyCreate()
    {
        $user_biolife = UserLifeBio::where('user_id', auth()->user()->id)->first();


        return view('back.users.info.life_biography.create', compact('user_biolife'));
    }

    public function UserInfoLifeBiographyStore(Request $request)
    {
        $user_life = UserLifeBio::where('user_id', auth()->user()->id)->first();
        if (!$user_life) {
            UserLifeBio::create([
                'user_id' => auth()->user()->id,
                'summerise_bio' => $request->summerise_bio ,
                'life_biography' => $request->life_biography
            ]);
        } else {

            $user_life->update([
                'life_biography' => $request->life_biography
            ]);
        }

        return redirect()->route('admin.users.info.lifebiography.create');
    }

    public function UserInfoLifeCalenderCreate()
    {
        $user_life_calendar = UserLifeCalender::where('user_id', auth()->user()->id)->get();

        return view('back.users.info.life_calender.create', compact('user_life_calendar'));
    }

    public function UserInfoLifeCalenderStore(Request $request)
    {
        // dd($request);
        UserLifeCalender::create([
            'user_id' => auth()->user()->id,
            'subject' => $request->subject,
            'day' => $request->day,
            'date' => $request->date,
            'value' => $request->value,
        ]);
        // dd($a);

        return redirect()->route('admin.users.info.lifecalender.create');
    }

    public function UserInfoLifeCalenderEdit($id)
    {
        $event = UserLifeCalender::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $user_life_calendar = UserLifeCalender::where('user_id', auth()->user()->id)->get();

        return view('back.users.info.life_calender.edit', compact('event', 'user_life_calendar'));
    }

    public function UserInfoLifeCalenderDelete($id)
    {
        $event = UserLifeCalender::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $event->delete();

        return redirect()->back()->with('success', 'رویداد با موفقیت حذف شد');
    }


    public function UserInfoLifeCalenderUpdate(Request $request, $id)
    {
        $event = UserLifeCalender::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $event->update([
            'subject' => $request->subject,
            'day' => $request->day,
            'date' => $request->date,
            'value' => $request->value,
        ]);

        return redirect()->route('admin.users.info.lifecalender.create')->with('success', 'رویداد با موفقیت ویرایش شد');
    }

    public function UserInfoWillCreate()
    {
        $wills = UserWill::where('user_id', auth()->id())->get();
        return view('back.users.info.will.create', compact('wills'));
    }

    public function UserInfoWillStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $userId = auth()->id();
        $uploadPath = public_path("uploads/userwill/{$userId}/");

        // ساخت وصیت‌نامه جدید
        $will = new UserWill();
        $will->user_id = $userId;
        $will->title = $request->title;
        $will->content = $request->content;

        // ذخیره موقت برای گرفتن id (در صورت نیاز)
        $will->save();

        // اگر عکس آپلود شد
        if ($request->hasFile('image')) {
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $filename);

            $relativePath = "uploads/userwill/{$userId}/{$filename}";

            $will->image = $relativePath;
            $will->save();
        }

        return redirect()->route('admin.users.info.will.create')->with('success', 'وصیت‌نامه جدید با موفقیت ذخیره شد.');
    }

    // نمایش فرم ویرایش
    public function UserInfoWillEdit($id)
    {
        $will = UserWill::findOrFail($id);
        return view('back.users.info.will.edit', compact('will'));
    }

    // ویرایش وصیت‌نامه
    public function UserInfoWillUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $will = UserWill::findOrFail($id);

        $will->title = $request->title;
        $will->content = $request->content;

        $uploadPath = public_path("uploads/userwill/{$will->user_id}/");

        if ($request->hasFile('image')) {
            if ($will->image && File::exists(public_path($will->image))) {
                File::delete(public_path($will->image));
            }

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $filename);

            $will->image = "uploads/userwill/{$will->user_id}/{$filename}";
        } else {
            if ($will->image && File::exists(public_path($will->image))) {
                File::delete(public_path($will->image));
                $will->image = null;
            }
        }

        $will->save();

        return redirect()->route('admin.users.info.will.create')->with('success', 'وصیت‌نامه با موفقیت ویرایش شد.');
    }

    public function UserInfoWillDelete($id)
    {
        $will = UserWill::findOrFail($id);

        if ($will->image && File::exists(public_path($will->image))) {
            File::delete(public_path($will->image));
        }

        $will->delete();

        return redirect()->route('admin.users.info.will.create')->with('success', 'وصیت‌نامه حذف شد.');
    }

    // public function UserInfoMemorialBookCreate()
    // {
    //     $memorials = UserMemorial::where('user_id', auth()->id())->get();
    //     return view('back.users.info.memorial.create', compact('memorials'));
    // }

    // public function UserInfoMemorialBookStore(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     $userId = auth()->id();
    //     $memorial = new UserMemorial();
    //     $memorial->user_id = $userId;
    //     $memorial->title = $request->title;

    //     if ($request->hasFile('image')) {
    //         $uploadPath = public_path("uploads/usermemorial/{$userId}/");
    //         if (!File::exists($uploadPath)) {
    //             File::makeDirectory($uploadPath, 0755, true);
    //         }

    //         $file = $request->file('image');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->move($uploadPath, $filename);

    //         $memorial->image = "uploads/usermemorial/{$userId}/{$filename}";
    //     }

    //     $memorial->save();
    //     return redirect()->route('admin.users.info.memorial.create')->with('success', 'دفتر یادبود ثبت شد.');
    // }

    // public function UserInfoMemorialBookEdit($id)
    // {
    //     $memorial = UserMemorial::findOrFail($id);
    //     return view('back.users.info.memorial.edit', compact('memorial'));
    // }

    // public function UserInfoMemorialBookUpdate(Request $request, $id)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);

    //     $memorial = UserMemorial::findOrFail($id);
    //     $memorial->title = $request->title;

    //     $uploadPath = public_path("uploads/usermemorial/" . auth()->id());

    //     if ($request->hasFile('image')) {
    //         if ($memorial->image && File::exists(public_path($memorial->image))) {
    //             File::delete(public_path($memorial->image));
    //         }

    //         if (!File::exists($uploadPath)) {
    //             File::makeDirectory($uploadPath, 0755, true);
    //         }

    //         $file = $request->file('image');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $file->move($uploadPath, $filename);

    //         $memorial->image = "uploads/usermemorial/" . auth()->id() . "/{$filename}";
    //     }

    //     $memorial->save();
    //     return redirect()->route('admin.users.info.memorial.create')->with('success', 'ویرایش انجام شد.');
    // }

    // public function UserInfoMemorialBookDelete($id)
    // {
    //     $memorial = UserMemorial::findOrFail($id);

    //     if ($memorial->image && File::exists(public_path($memorial->image))) {
    //         File::delete(public_path($memorial->image));
    //     }

    //     $memorial->delete();

    //     return redirect()->route('admin.users.info.memorial.create')->with('success', 'یادبود حذف شد.');
    // }

    public function UserInfoAnnouncementCreate()
    {
        $userId = auth()->id();
        $announcements = UserAnnouncement::where('user_id', $userId)->get();

        return view('back.users.info.announcement.create', compact('announcements'));
    }

    // ذخیره اطلاعیه جدید
    public function UserInfoAnnouncementStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $userId = auth()->id();

        $announcement = UserAnnouncement::create([
            'user_id' => $userId,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if ($request->hasFile('image')) {
            $uploadPath = public_path("uploads/announcements/{$userId}/");

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $filename);

            $relativePath = "uploads/announcements/{$userId}/{$filename}";
            $announcement->image = $relativePath;
            $announcement->save();
        }

        return redirect()->route('admin.users.info.announcement.create')->with('success', 'اطلاعیه ثبت شد.');
    }

    // نمایش فرم ویرایش اطلاعیه
    public function UserInfoAnnouncementEdit($id)
    {
        $announcement = UserAnnouncement::findOrFail($id);
        return view('back.users.info.announcement.edit', compact('announcement'));
    }

    // به‌روزرسانی اطلاعیه
    public function UserInfoAnnouncementUpdate(Request $request, $id)
    {
        $announcement = UserAnnouncement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $announcement->title = $request->title;
        $announcement->content = $request->content;

        $userId = auth()->id();
        $uploadPath = public_path("uploads/announcements/{$userId}/");

        if ($request->hasFile('image')) {
            // حذف عکس قبلی اگر وجود داشت
            if ($announcement->image && File::exists(public_path($announcement->image))) {
                File::delete(public_path($announcement->image));
            }

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $filename);

            $announcement->image = "uploads/announcements/{$userId}/{$filename}";
        } else {
            // اگر عکس جدید ارسال نشده، عکس قبلی حفظ شود
        }

        $announcement->save();

        return redirect()->route('admin.users.info.announcement.create')->with('success', 'اطلاعیه به‌روزرسانی شد.');
    }

    // حذف اطلاعیه
    public function UserInfoAnnouncementDelete($id)
    {
        $announcement = UserAnnouncement::findOrFail($id);

        if ($announcement->image && File::exists(public_path($announcement->image))) {
            File::delete(public_path($announcement->image));
        }

        $announcement->delete();

        return redirect()->route('admin.users.info.announcement.create')->with('success', 'اطلاعیه حذف شد.');
    }


    public function UserInfoNoticeCreate()
    {
        $notices = UserNotice::where('user_id', auth()->id())->get();
        return view('back.users.info.notice.create', compact('notices'));
    }

    public function UserInfoNoticeStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $userId = auth()->id();

        $notice = UserNotice::create([
            'user_id' => $userId,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if ($request->hasFile('image')) {
            $uploadPath = public_path("uploads/usernotice/{$userId}/");

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $filename);

            $notice->image = "uploads/usernotice/{$userId}/{$filename}";
            $notice->save();
        }

        return redirect()->route('admin.users.info.notice.create')->with('success', 'اطلاعیه ثبت شد.');
    }

    public function UserInfoNoticeEdit($id)
    {
        $notice = UserNotice::findOrFail($id);
        return view('back.users.info.notice.edit', compact('notice'));
    }

    public function UserInfoNoticeUpdate(Request $request, $id)
    {
        $notice = UserNotice::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $notice->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $userId = auth()->id();
        $uploadPath = public_path("uploads/usernotice/{$userId}/");

        if ($request->hasFile('image')) {
            if ($notice->image && File::exists(public_path($notice->image))) {
                File::delete(public_path($notice->image));
            }

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $filename);

            $notice->image = "uploads/usernotice/{$userId}/{$filename}";
            $notice->save();
        }

        return redirect()->route('admin.users.info.notice.create')->with('success', 'اطلاعیه بروزرسانی شد.');
    }

    public function UserInfoNoticeDelete($id)
    {
        $notice = UserNotice::findOrFail($id);

        if ($notice->image && File::exists(public_path($notice->image))) {
            File::delete(public_path($notice->image));
        }

        $notice->delete();

        return redirect()->route('admin.users.info.notice.create')->with('success', 'اطلاعیه حذف شد.');
    }

    public function UserInfoMessageManagementCreate()
    {
        $messages = UserMessage::where('user_id', auth()->user()->id)->get();
        return view('back.users.info.Message.create', compact('messages'));
    }

    // ذخیره پیام جدید
    public function UserInfoMessageManagementStore(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        UserMessage::create([
            'user_id' => auth()->user()->id,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.users.info.message.create')->with('success', 'پیام با موفقیت ذخیره شد.');
    }

    // نمایش فرم ویرایش پیام
    public function UserInfoMessageManagementEdit($id)
    {
        $message = UserMessage::where('user_id', auth()->user()->id)->findOrFail($id);
        return view('back.users.info.message.edit', compact('message'));
    }

    // بروزرسانی پیام
    public function UserInfoMessageManagementUpdate(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $message = UserMessage::where('user_id', auth()->user()->id)->findOrFail($id);
        $message->update([
            'content' => $request->content,
        ]);

        return redirect()->route('admin.users.info.message.create')->with('success', 'پیام با موفقیت بروزرسانی شد.');
    }

    // حذف پیام
    public function UserInfoMessageManagementDelete($id)
    {
        $message = UserMessage::where('user_id', auth()->user()->id)->findOrFail($id);
        $message->delete();

        return redirect()->route('admin.users.info.message.create')->with('success', 'پیام با موفقیت حذف شد.');
    }
}
