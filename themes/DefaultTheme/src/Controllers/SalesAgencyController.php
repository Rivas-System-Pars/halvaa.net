<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SalesAgency;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SalesAgencyController extends Controller
{
    public function index()
    {
        return view('front::pages.Sales-agency');
    }
	
	public function store(Request $request){
		
		$request->merge(['start_activity_date'=>$request->filled('start_activity_date') ? faTOen($request->start_activity_date) : null]);
		 $request->validate([
            'name' => 'required|string',
			'company_name' => 'nullable|string',
			 'registration_number' => 'nullable|string',
			 'activity_topic_description' => 'nullable|string',
			 'fax' => 'nullable|string',
			'start_activity_date' => ['required','regex:/^[1-9][0-9]{3}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/'],
			'mobile' => ['required','numeric','regex:/^(?:98|\+98|0098|0)?9[0-9]{9}$/'],
			 'phone_number' => 'nullable|numeric',
			 'email' => 'nullable|email',
			 'province' => 'required|string',
			 'city' => 'required|string',
			 'address' => 'required|string',
			 'website' => 'nullable|url',
			 'method_of_introduction' => 'nullable|string',
			 'level_of_education' => 'required|string',
			 'work_experience_description' => 'nullable|string',
			 'project_title' => 'nullable|string',
			 'description' => 'nullable|string',
			 'has_elling_software_products' => 'required|in:1,2',
			 'cv' => 'nullable|file|mimes:pdf|max:5120',
        ],[],[
			'name'=>"نام و نام خانوادگی",
			'company_name'=>"نام شرکت",
			 'registration_number'=>"شماره ثبت",
			 'activity_topic_description'=>"موضوع فعالیت",
			 'fax'=>"فکس",
			 'website'=>"وبسایت",
			 'method_of_introduction'=>"نحوه آشنایی با شرکت",
			 'start_activity_date'=>"تاریخ شروع فعالیت",
			 'mobile' => 'شماره موبایل',
			 'phone_number' => 'شماره تلفن',
			 'email' => 'پست الکترونیک',
			 'province' => 'استان',
			 'city' => 'شهر',
			 'address' => 'آدرس',
			 'description' => 'جزئیات',
			 'project_title' => 'عنوان قرارداد یا پروژه',
			 'has_elling_software_products' => 'حوزه فروش محصولات نرم افزاری',
			 'level_of_education' => 'میزان تحصیلات',
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
		SalesAgency::query()->delete();
		try{
			return DB::transaction(function () use ($request,$cv) {
				$salesAgency=SalesAgency::create([
					'company_name' => $request->company_name,
					 'registration_number' => $request->registration_number,
					 'activity_topic_description' => $request->activity_topic_description,
					 'fax' => $request->fax,
					'start_activity_date' => Jalalian::fromFormat('Y/m/d', $request->start_activity_date)->toCarbon(),
					 'website' => $request->website,
					'name' => $request->name,
					'project_title' => $request->project_title,
					'description' => $request->description,
					'method_of_introduction' => $request->method_of_introduction,
					'mobile' => $request->mobile,
					 'phone_number' => $request->phone_number,
					 'email' => $request->email,
					 'province' => $request->province,
					 'city' => $request->city,
					 'address' => $request->address,
					 'level_of_education' => $request->level_of_education,
					 'has_elling_software_products' => $request->filled('has_elling_software_products') && $request->has_elling_software_products ==2,
					 'work_experience_description' => $request->work_experience_description,
					 'cv' => $cv,
				]);
				return redirect()->route('front.sales-agency.index')->with(['success'=>"ثبت اطلاعات با موفقیت انجام شد"]);
			}); 
		}catch(\Throwable $e){
			return redirect()->route('front.sales-agency.index')->with(['error'=>"خطا در ثبت اطلاعات"]);
		}
	}
}
