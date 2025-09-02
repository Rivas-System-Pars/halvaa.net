<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CounselingForm;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CounselingFormController extends Controller
{
    public function index()
    {
        return view('front::pages.Counseling-Form');
    }
	
	public function store(Request $request){
		 $request->validate([
            'name' => 'required|string',
			'mobile' => ['required','numeric','regex:/^(?:98|\+98|0098|0)?9[0-9]{9}$/'],
			 'province' => 'required|string',
			 'city' => 'required|string',
			 'activity' => 'required|string',
			 'description' => ['required','string'],
        ],[],[
			'name'=>"نام و نام خانوادگی",
			 'mobile' => 'شماره موبایل',
			 'province' => 'استان',
			 'city'	=> 'شهر',
			 'activity'	=>	'حوزه فعالیت',
			 'description' => 'توضیحات تکمیلی موضوع مشاوره',
		]);
		CounselingForm::create([
			'name' => $request->name,
			'mobile' => $request->mobile,
			'province' => $request->province,
			 'city' => $request->city,
			 'activity' => $request->activity,
			 'description' => $request->description,
		]);
		return redirect()->route('front.counseling-form.index')->with(['success'=>"ثبت اطلاعات با موفقیت انجام شد"]);
	}
}
