<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Requests\Back\Installment\InstallmentRequest;
use App\Models\Installment;
use App\Models\Product;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function index()
    {
        $installments = Installment::query()->paginate(20);
        return view('back.installment.index', compact('installments'));
    }

    public function create()
    {
		$products = Product::pluck('title','id');
        return view('back.installment.create',compact('products'));
    }

    public function store(InstallmentRequest $request)
    {
        try {
            $installment = Installment::query()->create([
                'user_id' => auth()->id(),
                'title' => $request->title,
				'period' => $request->filled('period') ? $request->period : 1,
                'description' => $request->description,
                'prepayment_percentage' => $request->prepayment_percentage,
                'fee_percentage' => $request->fee_percentage,
                'installments_count' => $request->installments_count,
				'is_active' => $request->filled('is_active') && $request->is_active == 1,
            ]);
			if($request->filled('products') && count($request->products)){
				$installment->products()->attach($request->products);
			}
            return redirect()->route('admin.installments.index');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => trans("Internal server error")]);
        }
    }

    public function edit($installment)
    {
        $installment = Installment::query()->findOrFail($installment);
		$products = Product::pluck('title','id');
        return view('back.installment.edit', compact('installment','products'));
    }

    public function update(InstallmentRequest $request,$installment)
    {
        $installment = Installment::query()->findOrFail($installment);
        try {
            $installment->update([
                'title' => $request->title,
                'description' => $request->description,
				'period' => $request->filled('period') ? $request->period : 1,
                'prepayment_percentage' => $request->prepayment_percentage,
                'fee_percentage' => $request->fee_percentage,
                'installments_count' => $request->installments_count,
				'is_active' => $request->filled('is_active') && $request->is_active == 1,
            ]);
			$installment->products()->sync($request->get('products',[]));
            return redirect()->route('admin.installments.index');
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => trans("Internal server error")]);
        }
    }
	
	public function destroy($installment)
    {
        $installment = Installment::query()->findOrFail($installment);
		$installment->delete();
        return response('success');
    }
}
