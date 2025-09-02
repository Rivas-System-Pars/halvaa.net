<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\DemoRequest;

class DemoRequestController extends Controller
{
    public function index()
    {
		$products = Product::query()->published()->pluck('title','id');
        return view('front::pages.demo-request',compact('products'));
    }
	
	public function store(Request $request){
		 $request->validate([
            'name' => 'required|string',
			'mobile' => ['required','numeric','regex:/^(?:98|\+98|0098|0)?9[0-9]{9}$/'],
			 'email' => 'required|email',
			 'product' => ['required','exists:products,id'],
        ],[],[
			'name'=>"نام و نام خانوادگی",
			 'email' => 'پست الکترونیک',
			 'mobile' => 'شماره موبایل',
			 'product' => 'محصول',
		]);
		DemoRequest::create([
			'name' => $request->name,
			'mobile' => $request->mobile,
			'email' => $request->email,
			'product_id' => $request->product,
		]);
		return redirect()->route('front.demo-request.index')->with(['success'=>"ثبت اطلاعات با موفقیت انجام شد"]);
	}
}
