<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\DemoRequest;
use Illuminate\Http\Request;

class DemoRequestController extends Controller
{
	public function __construct()
    {
        $this->middleware('can:demo-request');
    }
	
    public function index()
    {
        $demoRequests = DemoRequest::latest()->paginate(10);
        return view('back.demo-request.index', compact('demoRequests'));
    }
	
	public function show($id)
    {
        $demoRequestItem = DemoRequest::findOrFail($id);
		if(is_null($demoRequestItem->viewed_at)) $demoRequestItem->update(['viewed_at'=>now()]);
        return view('back.demo-request.show', compact('demoRequestItem'));
    }

    public function destroy($id)
    {
        $demoRequestItem = DemoRequest::findOrFail($id);
        $demoRequestItem->delete();
    }
}
