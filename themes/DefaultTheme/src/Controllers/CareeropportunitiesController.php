<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Careeropportunities;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class CareeropportunitiesController extends Controller
{
	public function index()
    {
        return view('front::pages.careeropportunities');
    }

	public function store(Request $request){
		$request->merge(['birth_of_date'=>$request->filled('birth_of_date') ? faTOen($request->birth_of_date) : null]);
		 $request->validate([
            'name' => 'required|string',
			'birth_of_date' => ['required','regex:/^[1-9][0-9]{3}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/'],
			'is_married' => 'required|in:1,2',
			'military_status' => 'nullable|string',
			'mobile' => ['required','numeric','regex:/^(?:98|\+98|0098|0)?9[0-9]{9}$/'],
			 'phone_number' => 'nullable|string',
			 'email' => 'nullable|email',
			 'province' => 'nullable|string',
			 'city' => 'nullable|string',
			 'address' => 'nullable|string',
			 'level_of_education' => 'required|string',
			 'field_of_education' => 'nullable|string',
			 'education_place' => 'nullable|string',
			 'has_work_experience' => 'required|in:1,2',
			 'work_experience_description' => 'nullable|string',
			 'cv' => 'nullable|file|mimes:pdf|max:5120',
        ],[],[
			'name'=>"نام و نام خانوادگی",
			'birth_of_date'=>"تاریخ تولد",
			 'is_married'=>"وضعیت تاهل",
			 'military_status'=>"وضعیت نظام وظیفه",
			 'mobile' => 'شماره موبایل',
			 'phone_number' => 'شماره تلفن',
			 'email' => 'پست الکترونیک',
			 'province' => 'استان',
			 'city' => 'شهر',
			 'address' => 'آدرس',
			 'level_of_education' => 'میزان تحصیلات',
			 'field_of_education' => 'رشته تحصیلی',
			 'education_place' => 'محل تحصیل',
			 'has_work_experience' => 'سابقه کار',
			 'work_experience_description' => 'سوابق کاری',
			 'cv' => 'فایل رزومه',
		]);
		$cv=null;
		if ($request->hasFile('cv')) {
            $file = $request->cv;
            $name = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $request->cv->storeAs('cv', $name,['disk' => 'downloads']);

            $cv = '/cv/' . $name;
        }
		Careeropportunities::create([
			'name' => $request->name,
			'birth_of_date' => Jalalian::fromFormat('Y/m/d', $request->birth_of_date)->toCarbon(),
			'is_married' => $request->filled('is_married') && $request->is_married ==2,
			'military_status' => $request->military_status,
			'mobile' => $request->mobile,
			 'phone_number' => $request->phone_number,
			 'email' => $request->email,
			 'province' => $request->province,
			 'city' => $request->city,
			 'address' => $request->address,
			 'level_of_education' => $request->level_of_education,
			 'field_of_education' => $request->field_of_education,
			 'education_place' => $request->education_place,
			 'has_work_experience' => $request->filled('has_work_experience') && $request->has_work_experience ==1,
			 'work_experience_description' => $request->work_experience_description,
			 'cv' => $cv,
		]);
		return redirect()->route('front.careeropportunities.index')->with(['success'=>"ثبت اطلاعات با موفقیت انجام شد"]);
	}
}
