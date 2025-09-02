<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // ثبت یا به‌روزرسانی امتیاز
        Rating::updateOrCreate(
            ['product_id' => $request->product_id, 'user_id' => auth()->id()],
            ['rating' => $request->rating]
        );

        // محاسبه میانگین جدید
        $product = Product::find($request->product_id);
        $newAverage = $product->ratings()->avg('rating');
		
		$product->update([
			'avg_rating_star'	=> $newAverage,
		]);

        return response()->json(['message' => 'امتیاز ثبت شد', 'newAverage' => $newAverage]);
    }
}
