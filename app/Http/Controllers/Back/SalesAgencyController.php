<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\SalesAgency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SalesAgencyController extends Controller
{
	public function __construct()
    {
        $this->middleware('can:sales-agency');
    }
	
    public function index()
    {
        $salesAgency = SalesAgency::latest()->paginate(10);
        return view('back.sales-agency.index', compact('salesAgency'));
    }
	
	public function show($id)
    {
        $salesAgencyItem = SalesAgency::findOrFail($id);
		if(is_null($salesAgencyItem->viewed_at)) $salesAgencyItem->update(['viewed_at'=>now()]);
        return view('back.sales-agency.show', compact('salesAgencyItem'));
    }
	
	public function download($id)
    {
        $salesAgencyItem = SalesAgency::findOrFail($id);
		abort_if(!Storage::disk('downloads')->exists($salesAgencyItem->cv),404);
		return Storage::disk('downloads')->download($salesAgencyItem->cv);
    }

public function destroy($id)
{
    $salesAgencyItem = SalesAgency::findOrFail($id);
    $salesAgencyItem->delete();
    return redirect()->route('admin.sales-agency.index')->with('success');
}
}
