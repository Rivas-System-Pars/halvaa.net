<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\ProductGift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProductGiftController extends Controller
{
    /**
     * @param Request $request
     * @param $productGift
     * @return JsonResponse
     */
    public function destroy(Request $request, $productGift)
    {
        $productGift = ProductGift::query()->findOrFail($productGift);
        try {
            $productGift->delete();
            return response()->json([
                'msg' => "عملیات با موفقیت انجام شد",
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'msg' => "خطا در انجام عملیات",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $productGift
     * @param $product
     * @return JsonResponse
     */
    public function detachProduct($productGift , $product)
    {
        /** @var ProductGift $productGift */
        $productGift = ProductGift::query()->findOrFail($productGift);
        try {
            if ($productGift->products->contains('id',$product)) $productGift->products()->detach($product);
            return response()->json([
                'msg' => "عملیات با موفقیت انجام شد",
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'msg' => "خطا در انجام عملیات",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
