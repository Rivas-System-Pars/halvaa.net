<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\UserComments;
use App\Models\UserMemorial;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Color\Color;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class UserProfileController extends Controller
{

    public function alluser(Request $request)
    {
        // dd('a');
        $users = User::where('level', 'admin')->get();
        // dd($users);
        return view('front::user.profile.alluser', compact('users'));
    }

    public function showprofile(Request $request, $id)
    {
        $user = User::with([
            'birthCity',
            'deathCity',
            'profileImage',
            'orders.items.product',
            'familyTree',
        ])->findOrFail((int) $id);

        if ($user->level == 'user') {
            toastr()->error("شما قادر به دیدن این صفحه نیستید");
            return redirect()->back();
        }

        if ($user->id !== auth()->id()) {
            $user->increment('profile_views');
        }

        $follow = null;
        if ($user && $user->id !== $user->id) {
            $follow = \App\Models\Follow::where('follower_id', $user->id)
                ->where('following_id', $user->id)
                ->first();
        }
        $userBanners = \App\Models\UserBanner::where('user_id', $user->id)
            ->latest()
            ->get();
        // ✅ Check if user has paid for a product in category 97
        // $hasQrCodeProduct = collect($user->orders)
        //     ->filter(fn($order) => strtolower($order->status) === 'paid')
        //     ->flatMap(fn($order) => $order->items)
        //     ->contains(fn($item) => optional($item->product)->category_id == 97);

        $hasQrCodeProduct = true;

        $postCount = $user->posts()->count();
        $followersCount = $user->followers()->count();
        $followingCount = $user->followings()->count();
        $profileImage = $user->profileImage ? env('APP_URL') . '/' . $user->profileImage->image : null;
        $familyTreeUrl = $user->familyTree ? $user->familyTree : null;
        $fullName = $user->first_name . ' ' . $user->last_name;
        $bio = $user->bio ?? '';
        $profileview = $user->profile_views;

        $weatherData = null;






        /*
              if ($user->latitude && $user->longitude) {
                $response = Http::withoutVerifying()->get("https://api.dastyar.io/express/weather?lat=$user->latitude&lng=$user->longitude&theme=glass");


                if ($response->ok()) {

                    $rawWeather = $response->json();
                    [$desc, $icon] = $getWeatherDescription($rawWeather[0]['weather']['id'] ?? 800);

                    $weatherData = [
                        'temperature' => $rawWeather[0]['current'],
                        'description' => $rawWeather[0]['weather']['description'],
                        'icon' => $icon,
                        'time' => $rawWeather[0]['dateTitle']
                    ];
                }
            }

             */

        $related_users = User::query()
            ->with('profileImage')
            ->select('users.id', 'users.first_name', 'users.last_name', 'uo.option_name as relation_name') // pick needed columns
            ->join('user_options as uo', function ($join) use ($user) {
                $join->on('uo.option_value', '=', 'users.id')
                    ->where('uo.user_id', $user->id);
            })
            ->when(auth()->check(), function ($q) use ($user) {
                $q->whereNotIn('users.id', [$user->id, auth()->id()]);
            }, function ($q) use ($user) {
                $q->where('users.id', '!=', $user->id);
            })
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'uo.option_name')
            ->get();






        $qrCodeBase64 = null;

        if ($hasQrCodeProduct) {
            $profileUrl = url('/user/profile/' . $user->id);

            $qrCode = QrCode::create($profileUrl)
                ->setEncoding(new Encoding('UTF-8'))
                // ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh)
                ->setSize(100)
                ->setMargin(0)
                // ->setRoundBlockSizeMode(new  RoundBlockSizeModeMargin)
                ->setForegroundColor(new Color(0, 0, 0))
                ->setBackgroundColor(new Color(255, 255, 255));

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            $qrCodeBase64 = base64_encode($result->getString());
        }
        $data = [
            'user' => $user,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
            'postCount' => $postCount,
            'profile_image' => $profileImage,
            'fullName' => $fullName,
            'bio' => $bio,
            'qrCodeBase64' => $qrCodeBase64,
            'profileview' => $profileview,
            'hasQrCodeProduct' => $hasQrCodeProduct,
            'family_tree' => $familyTreeUrl,
        ];

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($data);
        }

        return view('front::user.profile.profilewithlogin', compact(
            'user',
            'followersCount',
            'followingCount',
            'postCount',
            'profileImage',
            'fullName',
            'bio',
            'qrCodeBase64',
            'profileview',
            'hasQrCodeProduct',
            'userBanners',
            'familyTreeUrl',
            'related_users',
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'national_code' => 'nullable|string|max:10',
            'birth' => 'nullable|string|max:255',
            'death' => 'nullable|string|max:255',
            //'birth_city_id' => 'nullable|string|max:255',
            //'death_city_id' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_private' => 'nullable|boolean',
        ]);

        $user->update([
            'first_name' => $request->first_name ?? $user->first_name,
            'last_name' => $request->last_name ?? $user->last_name,
            'national_code' => $request->national_code ?? $user->national_code,
            'birth' => $request->birth ?? $user->birth,
            'death' => $request->death ?? $user->death,
            // 'birth_city_id' => $request->birth_city_id ?? $user->birth_city_id,
            //'death_city_id' => $request->death_city_id ?? $user->death_city_id,
            'bio' => $request->bio ?? null,
            'is_private' => $request->is_private ?? $user->is_private,
        ]);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $userId = $user->id;
            $folderPath = public_path("uploads/UserProfile/{$userId}");

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            $oldMedia = $user->media()->first();
            if ($oldMedia && file_exists(public_path($oldMedia->image))) {
                unlink(public_path($oldMedia->image));
                $oldMedia->delete();
            }

            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($folderPath, $filename);
            $relativePath = "uploads/UserProfile/{$userId}/{$filename}";

            $user->media()->create([
                'image' => $relativePath,
            ]);
        }

        $user->load('media');

        $profileImage = null;
        if ($user->media()->exists()) {
            $media = $user->media()->first();
            $profileImage = env('APP_URL') . '/' . $media->image;
        }

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully!',
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'national_code' => $user->national_code,
                'birth' => $user->birth,
                'death' => $user->death,
                //'birth_city_id' => $user->birth_city_id,
                //'death_city_id' => $user->death_city_id,
                'bio' => $user->bio,
                'profile_image' => $profileImage,
            ],
        ]);
    }

    //Memorials(Parham)

    public function indexmemorials($target_user_id)
    {
        $memorials = UserMemorial::with(['writer.profileImage'])
            ->where('target_user_id', $target_user_id)
            ->latest()
            ->get()
            ->map(function ($memorial) {
                if ($memorial->writer && $memorial->writer->relationLoaded('profileImage')) {
                    // فقط فیلد image رو داخل writer اضافه کن
                    $memorial->writer->setAttribute(
                        'profile_image',
                        $memorial->writer->profileImage
                        ? env('APP_URL') . '/' . $memorial->writer->profileImage->image
                        : null
                    );
                    // حذف خود profileImage از خروجی JSON
                    unset($memorial->writer->profileImage);
                }
                return $memorial;
            });

        $user = User::findOrFail($target_user_id);

        return response()->json([
            'success' => true,
            'memorials' => $memorials,
            'username' => $user->username,
            'profile_image' => $user->profileImage
                ? env('APP_URL') . '/' . $user->profileImage->image
                : env('APP_URL') . '/' . 'back/app-assets/images/portrait/small/default.jpg',

        ]);
    }




    public function storememorials(Request $request, $target_user_id)
    {

        $request->validate([
            'text' => 'required|string|max:1000',
        ]);
        $user = auth()->user();
        $count = UserMemorial::where('writer_user_id', $user->id)
            ->where('target_user_id', $target_user_id)
            ->count();

        if ($count >= 1) {
            return response()->json([
                'success' => false,
                'message' => 'شما نمیتوانید بیشتر از  یک یادبود وارد کنید'
            ], 403);
        }
        $memorial = new UserMemorial();
        $memorial->target_user_id = $target_user_id;
        $memorial->writer_user_id = auth()->id();
        $memorial->text = $request->text;



        $memorial->save();

        return response()->json([
            'success' => true,
            'memorial' => $memorial,
            'username' => $user->username ?? $user->first_name . ' ' . $user->last_name,
            'profile_image' => $user->profileImage ? env('APP_URL') . '/' . $user->profileImage->image : env('APP_URL') . '/' . 'back/app-assets/images/portrait/small/default.jpg'
        ]);
    }

    // public function showmemorials($id)
    // {
    //     $memorial = UserMemorial::with(['writer', 'target'])->findOrFail($id);

    //     return response()->json([
    //         'success' => true,
    //         'memorial' => $memorial,
    //         'profile_image' => $user->profileImage ? env('APP_URL') . '/' . $user->profileImage->image : null

    //     ]);
    // }


    //------------------------user comments
    public function indexusercomments($target_user_id)
    {
        $comments = UserComments::with(['writer.profileImage'])
            ->where('target_user_id', $target_user_id)
            ->latest()
            ->get()
            ->map(function ($comments) {
                if ($comments->writer && $comments->writer->relationLoaded('profileImage')) {
                    // فقط فیلد image رو داخل writer اضافه کن
                    $comments->writer->setAttribute(
                        'profile_image',
                        $comments->writer->profileImage
                        ? env('APP_URL') . '/' . $comments->writer->profileImage->image
                        : null
                    );
                    // حذف خود profileImage از خروجی JSON
                    unset($comments->writer->profileImage);
                }
                return $comments;
            });

        $user = User::findOrFail($target_user_id);

        return response()->json([
            'success' => true,
            'memorials' => $comments,
            'username' => $user->username,
            'profile_image' => $user->profileImage
                ? env('APP_URL') . '/' . $user->profileImage->image
                : env('APP_URL') . '/' . 'back/app-assets/images/portrait/small/default.jpg',

        ]);
    }


    public function storeusercomments(Request $request, $target_user_id)
    {

        $request->validate([
            'text' => 'required|string|max:1000',
        ]);
        $user = auth()->user();
        $count = UserComments::where('writer_user_id', $user->id)
            ->where('target_user_id', $target_user_id)
            ->count();


        $comments = new UserComments();
        $comments->target_user_id = $target_user_id;
        $comments->writer_user_id = auth()->id();
        $comments->text = $request->text;



        $comments->save();

        return response()->json([
            'success' => true,
            'memorial' => $comments,
            'username' => $user->username ?? $user->first_name . ' ' . $user->last_name,
            'profile_image' => $user->profileImage ? env('APP_URL') . '/' . $user->profileImage->image : env('APP_URL') . '/' . 'back/app-assets/images/portrait/small/default.jpg'
        ]);
    }

    public function destroyusercomments($id)
    { {
            $comment = UserComments::findOrFail($id);
            $user = auth()->user();

            $isWriter = $comment->writer_user_id == $user->id;
            $isProfileOwner = $comment->target_user_id == $user->id;
            $isAdmin = method_exists($user, 'hasRole') ? $user->hasRole('admin') : false;

            if (!($isWriter || $isProfileOwner || $isAdmin)) {
                return response()->json([
                    'success' => false,
                    'message' => 'اجازه حذف این نظر را ندارید.',
                ], 403);
            }

            $comment->delete(); // اگر SoftDeletes دارید، سافت دیلیت می‌شود

            return response()->json([
                'success' => true,
                'message' => 'نظر حذف شد.',
                'deleted_id' => (int) $id,
            ]);
        }
    }

}
