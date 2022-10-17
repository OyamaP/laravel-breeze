<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Stock;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
        // 販売停止中商品の参照対策
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('item');
            if(!is_null($id)) {
                $itemId = Product::availableItems()->where('products.id', $id)->exists();
                if(!$itemId) abort(404);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $products = Product::availableItems()->sortOrder($request->sort)->paginate($request->pagination ?? 20); // ローカルスコープ
        return view('user.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        // image1~4のfilenameを配列で取得
        $filenames = array_filter([ // 配列内の空白要素を削除
            $product->imageFirst->filename ?? '', $product->imageSecond->filename ?? '', $product->imageThird->filename ?? '', $product->imageFourth->filename ?? '',
        ]);
        if(count($filenames) === 0) $filenames[] = ''; // 空の場合はNO IMAGE1枚となるよう空要素を1つ追加

        // 在庫を取得し表示する最大値を9とする
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');
        $quantity = 9 < $quantity ? 9 : $quantity;

        return view('user.show', compact('product', 'filenames', 'quantity'));
    }

}
