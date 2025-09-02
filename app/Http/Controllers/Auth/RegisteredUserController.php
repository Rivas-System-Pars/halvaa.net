<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UserRegisterRequest;
use App\Models\Province;
use App\Models\Countries;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Gallery;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $view = config('front.pages.register');


        if (!$view) {
            abort(404);
        }

        $provinces = Province::with('cities')->get();
        $countries = Countries::orderBy('name')->get();
        return view($view, compact('provinces' , 'countries'));
    }
    public function usercreate()
    {
        return view('front::auth.userregister');
    }

    // public function usercreate()
    // {
    //     $view = config('front.pages.userregister');

    //     if (!$view) {
    //         abort(404);
    //     }

    //     $provinces = Province::with('cities')->get();
    //     return view($view, compact('provinces'));
    // }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */


    public function store(RegisterRequest $request)
    {

        $data = $request->validated();
        // Hash password
        $data['password'] = Hash::make($data['password']);
        $data['level'] ='admin';
        // dd($data);

        // Temporarily remove 'image' key from validated data
        unset($data['image']);

        // Create user
        $user = User::create($data);

        // Handle profile image if uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/ProfilePic/' . $user->id;

            // Get file size BEFORE move
            $size = $file->getSize();

            // Create directory if it doesn't exist
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0777, true);
            }

            // Move the uploaded file
            $file->move(public_path($path), $filename);

            // Save file data in related model (Gallery or Media)
            $user->profileImage()->create([
                'name' => $file->getClientOriginalName(),
                'image' => $path . '/' . $filename,
                'type' => 'image',
                'size' => $size,
            ]);

        }

        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => 4
        ]);

        // Trigger registered event & login
        event(new Registered($user));
        Auth::login($user);

        return response('success');
    }



    public function userstore(UserRegisterRequest $request)
    {
        $data = $request->validated();
        // Hash password
        $data['password'] = Hash::make($data['password']);

        // Temporarily remove 'image' key from validated data
        unset($data['image']);

        // Create user
        $user = User::create($data);

        // Handle profile image if uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/ProfilePic/' . $user->id;

            // Get file size BEFORE move
            $size = $file->getSize();

            // Create directory if it doesn't exist
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0777, true);
            }

            // Move the uploaded file
            $file->move(public_path($path), $filename);

            // Save file data in related model (Gallery or Media)
            $user->profileImage()->create([
                'name' => $file->getClientOriginalName(),
                'image' => $path . '/' . $filename,
                'type' => 'image',
                'size' => $size,
            ]);

        }



        // Trigger registered event & login
        event(new Registered($user));
        Auth::login($user);

        return response('success');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
}
