<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\CounselingForm;
use Illuminate\Http\Request;

class CounselingFormController extends Controller
{
	public function __construct()
    {
        $this->middleware('can:counseling-form');
    }
	
    public function index()
    {
        $counselingForms = CounselingForm::latest()->paginate(10);
        return view('back.counseling-form.index', compact('counselingForms'));
    }
	
	public function show($id)
    {
        $counselingFormItem = CounselingForm::findOrFail($id);
		if(is_null($counselingFormItem->viewed_at)) $counselingFormItem->update(['viewed_at'=>now()]);
        return view('back.counseling-form.show', compact('counselingFormItem'));
    }
}
