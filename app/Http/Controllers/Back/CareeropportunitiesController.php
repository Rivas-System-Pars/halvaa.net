<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Careeropportunities;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CareeropportunitiesController extends Controller
{
	public function __construct()
    {
        $this->middleware('can:careeropportunities');
    }
	
    public function index()
    {
        $careeropportunities = Careeropportunities::latest()->paginate(10);
        return view('back.careeropportunities.index', compact('careeropportunities'));
    }
	
	public function show($id)
    {
        $careeropportunitiesItem = Careeropportunities::findOrFail($id);
		if(is_null($careeropportunitiesItem->viewed_at)) $careeropportunitiesItem->update(['viewed_at'=>now()]);
        return view('back.careeropportunities.show', compact('careeropportunitiesItem'));
    }
	
	public function download($id)
    {
        $careeropportunitiesItem = Careeropportunities::findOrFail($id);
		abort_if(!Storage::disk('downloads')->exists($careeropportunitiesItem->cv),404);
		return Storage::disk('downloads')->download($careeropportunitiesItem->cv);
    }

public function destroy($id)
{
    $careeropportunitiesItem = Careeropportunities::findOrFail($id);
    $careeropportunitiesItem->delete();

    return redirect()->route('admin.careeropportunities.index')
                     ->with('success', 'اطلاعات با موفقیت حذف شد.');
}

}
