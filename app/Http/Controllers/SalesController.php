<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Sale;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        return DB::transaction(function() use ($request){

            $productId = $request->input('product_id');
            $quantity = $request->input('quantity', 1);
            $product = Product::find($productId);

            if (!$product) {
                return response()->json(['message' => '商品が存在しません'], 404);
            }
            if ($product->stock < $quantity) {
                return response()->json(['message' => '商品が在庫不足です'], 400);
            }

            try {
                // トランザクション内で在庫減少と売上レコードの追加を行う
                $product->stock -= $quantity;
                $product->save();
                $sale = new Sale([
                    'product_id' => $productId,
                ]);
                $sale->save();
            } catch (Exception $e) {
                // 何らかのエラーが発生した場合、トランザクションをロールバック
                DB::rollBack();
                return response()->json(['message' => '購入に失敗しました'], 500);
            }

            // トランザクションが正常に終了した場合、コミット
            DB::commit();
            return response()->json(['message' => '購入成功']);
        });
    }
}
