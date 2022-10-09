<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Stock;
use App\Models\PrimaryCategory;
use App\Models\Owner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProductRequst;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');
        // ログインしたオーナー権限ではない画像情報の参照対策
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('product');
            if(!is_null($id)) {
                $productsOwnerId = Product::findOrFail($id)->shop->owner->id;
                // owners.id !== products.shop_id->owner_id
                if(Auth::id() !== (int)$productsOwnerId) abort(404);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 単一Ownerから複数のShop(collection)を取得する
        // $shops = Owner::with(['shop.product.imageFirst'])->where('id', Auth::id())->first()->shop;
        // ※ 現状Shopのページネーションしかできない
        $shops = Shop::with(['product.imageFirst'])->where('owner_id', Auth::id())->get();
        return view('owner.products.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shops = Shop::where('owner_id', Auth::id())->select('id', 'name')->get();
        $images = Image::where('owner_id', Auth::id())->select('id', 'title', 'filename')->orderBy('updated_at', 'desc')->get();
        $categories = PrimaryCategory::with('secondary')->get();

        return view('owner.products.create', compact('shops', 'images', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\ProductRequst  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequst $request)
    {
        try
        {
            DB::transaction(function () use($request) {
                $product = Product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'image2' => $request->image2,
                    'image3' => $request->image3,
                    'image4' => $request->image4,
                    'is_selling' => $request->is_selling,
                ]);
                Stock::create([
                    'product_id' => $product->id,
                    'type' => 1,
                    'quantity' => $request->quantity,
                ]);
            }, 2);
        }
        catch(Throwable $e)
        {
            Log::error($e);
            throw $e;
        }

        return redirect()
            ->route('owner.products.index')
            ->with([
                'message' => 'Success: Regist Product',
                'status' => 'info',
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');
        $shops = Shop::where('owner_id', Auth::id())->select('id', 'name')->get();
        $images = Image::where('owner_id', Auth::id())->select('id', 'title', 'filename')->orderBy('updated_at', 'desc')->get();
        $categories = PrimaryCategory::with('secondary')->get();

        return view('owner.products.edit', compact(
            'product', 'quantity', 'shops', 'images', 'categories'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\ProductRequst  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequst $request, $id)
    {
        $request->validate([
            'current_quantity' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');
        $is_quantity_error = false;
        // 編集中に在庫数が変動した場合
        if($request->current_quantity !== $quantity) {
            $is_quantity_error = true;
            $message = 'Notice: Stock has been changed by another action. Please check again';
        }
        // 減算時に在庫数が0未満になる場合
        if($request->type === \Constant::PRODUCT_LIST['sub'] && $quantity - $request->quantity < 0) {
            $is_quantity_error = true;
            $message = 'Error: Stock cannot be less than 0';
        }
        // エラー判定
        if($is_quantity_error) {
            $id = $request->route()->parameter('product');
            return redirect()->route('owner.products.edit', ['product' => $id])
                ->with([
                    'message' => $message,
                    'status' => 'alert',
                ]);
        }

        try
        {
            DB::transaction(function () use($request, $product) {
                // update product
                $product->name = $request->name;
                $product->information = $request->information;
                $product->price = $request->price;
                $product->sort_order = $request->sort_order;
                $product->shop_id = $request->shop_id;
                $product->secondary_category_id = $request->category;
                $product->image1 = $request->image1;
                $product->image2 = $request->image2;
                $product->image3 = $request->image3;
                $product->image4 = $request->image4;
                $product->is_selling = $request->is_selling;
                $product->save();

                // 在庫数変動
                $newQuantity = $request->type === \Constant::PRODUCT_LIST['add'] ? $request->quantity : ''; // 正数
                $newQuantity = $request->type === \Constant::PRODUCT_LIST['sub'] ? $request->quantity * -1 : $newQuantity; // 負数
                Stock::create([
                    'product_id' => $product->id,
                    'type' => $request->type,
                    'quantity' => $newQuantity,
                ]);
            }, 2);
        }
        catch(Throwable $e)
        {
            Log::error($e);
            throw $e;
        }

        return redirect()->route('owner.products.index')
            ->with([
                'message' => 'Success: Update Product',
                'status' => 'info',
            ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()
            ->route('owner.products.index')
            ->with([
                'message' => 'Success: Delete Product',
                'status' => 'alert',
            ]);
    }
}
