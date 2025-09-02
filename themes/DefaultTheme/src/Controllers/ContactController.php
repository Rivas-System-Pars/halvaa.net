<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Models\Contact;
use App\Events\ContactCreated as EventsContactCreated;
use App\Http\Controllers\Controller;
use App\Notifications\Contact\ContactCreated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class ContactController extends Controller
{
    public function index()
    {
        return view('front::contact');
    }

public function store(Request $request)
{
    $this->validate($request, [
        'name'    => 'required|string|max:191',
        'email'   => 'required|string|email|max:191',
        'subject' => 'required|string|max:191',
        'captcha' => ['required', 'captcha'],
        'message' => 'required|string|max:2000',
        'mobile'  => ['nullable','string','max:20','regex:/^(?:\+?98|0)?9\d{9}$/'],
        'image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // normalize mobile if needed
    $mobile = $request->mobile;
    if ($mobile) {
        $mobile = preg_replace('/\s+/', '', $mobile);
        if (preg_match('/^09\d{9}$/', $mobile)) {
            $mobile = '+98' . substr($mobile, 1);
        } elseif (preg_match('/^9\d{9}$/', $mobile)) {
            $mobile = '+98' . $mobile;
        }
    }

    $data = [
        'name'    => $request->name,
        'email'   => $request->email,
        'subject' => $request->subject,
        'message' => $request->message,
        'mobile'  => $mobile,
    ];

    if ($request->hasFile('image')) {
        $file     = $request->file('image');
        $filename = time().'_'.$file->getClientOriginalName();

        // save directly to public/uploads/Contacts
        $destination = public_path('uploads/Contacts');
        if (!\File::exists($destination)) {
            \File::makeDirectory($destination, 0755, true);
        }

        $file->move($destination, $filename);

        // save relative path in DB
        $data['image'] = 'uploads/Contacts/'.$filename;
    }

    $contact = Contact::create($data);

    $admins = User::whereIn('level', ['admin', 'creator'])->get();
    Notification::send($admins, new ContactCreated($contact));

    event(new EventsContactCreated($contact));

    return response(['message' => 'success']);
}


}
