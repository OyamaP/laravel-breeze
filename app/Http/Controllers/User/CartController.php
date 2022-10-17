<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\User;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $products = User::findOrFail(Auth::id())->products;
        $totalPrice = $products->reduce(function($total, $product) {
            return $total + $product->price * $product->pivot->quantity;
        }, 0);
        $this->resetStripe($request);
        // $this->checkStock($products);

        return view('user.cart', compact('products', 'totalPrice'));
    }

    public function add(Request $requst)
    {
        $itemInCart = Cart::where('product_id', $requst->product_id)->where('user_id', Auth::id())->first();
        $quantity = Stock::where('product_id', $requst->product_id)->sum('quantity');

        // 実在庫 < カート内在庫 => カート内在庫を実在庫に設定
        if( $quantity < $itemInCart->quantity ?? 0 + $requst->quantity ) {
            $itemInCart->quantity = $quantity;
            $itemInCart->save();
            return redirect()->route('user.cart.index');
        }

        // Userアカウントがリクエスト商品をCartに入れているか確認
        if($itemInCart) { // レコードの商品量を加算更新
            $itemInCart->quantity += $requst->quantity;
            $itemInCart->save();
        } else { // 新規レコード作成
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $requst->product_id,
                'quantity' => $requst->quantity,
                'is_paying' => false,
            ]);
        }

        return redirect()->route('user.cart.index');
    }

    public function delete(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);
        if(0 < $request->quantity) { // カート内の商品量更新
            $cart = Cart::where('product_id', $id)->where('user_id', Auth::id())->first();
            $cart->quantity = $request->quantity;
            $cart->save();
        } else { // カート内から商品削除
            Cart::where('product_id', $id)->where('user_id', Auth::id())->delete();
        }

        return redirect()->route('user.cart.index');
    }

    public function checkout(Request $request)
    {
        $this->resetStripe($request);

        $products = User::findOrFail(Auth::id())->products;
        // $this->checkStock($products);

        // Stripe API Document
        // https://stripe.com/docs/checkout/quickstart?lang=php
        $lineItems = $products->map(function($product) {
            return [ // https://stripe.com/docs/payments/checkout/migrating-prices
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                        'description' => $product->information,
                    ],
                    'unit_amount' => $product->price,
                ],
                'quantity' => $product->pivot->quantity
            ];
        })->toArray(); // Collection -> Array

        // 決済入力前に在庫減少
        foreach($products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['paying'],
                'quantity' => $product->pivot->quantity * -1,
            ]);
            $cart = Cart::where('product_id', $product->id)->where('user_id', Auth::id())->first();
            $cart->is_paying = true;
            $cart->save();
        }

        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [$lineItems],
                'mode' => 'payment',
                'success_url' => route('user.cart.success'),
                'cancel_url' => route('user.cart.cancel'),
            ]);
            $request->session()->put('stripe_session', $session->id); // stripe session を保存(後にsession削除等で利用)
            $publicKey = env('STRIPE_PUBLIC_KEY');
            return view('user.checkout', compact('session', 'publicKey'));
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error("An invalid request occurred: {$e}");
        } catch (Throwable $e) {
            Log::error("Another problem occurred, maybe unrelated to Stripe: {$e}");
        }
        // エラー処理
        $this->cancel($request);
    }

    public function success(Request $request)
    {
        Cart::where('user_id', Auth::id())->delete();
        $request->session()->forget('stripe_session');
        return redirect()->route('user.items.index');
    }

    public function cancel(Request $request)
    {
        $this->resetStripe($request);
        return redirect()->route('user.cart.index');
    }

    // Stripe Session に関するリセット処理
    public function resetStripe(Request $request)
    {
        if($request->session()->has('stripe_session')) {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            $stripe->checkout->sessions->expire($request->session()->pull('stripe_session'));
            $this->backToStock();
        }
    }

    // キャンセル時などの在庫戻し処理
    public function backToStock()
    {
        $user = User::findOrFail(Auth::id());
        // 決済入力前の在庫に戻す
        foreach($user->products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['cancel'],
                'quantity' => $product->pivot->quantity,
            ]);
            $cart = Cart::where('product_id', $product->id)->where('user_id', Auth::id())->first();
            $cart->is_paying = false;
            $cart->save();
        }
    }

    // カートの数量と実際の在庫を比較する
    // public function checkStock(Collection $products) // $products = User::findOrFail(Auth::id())->products;
    // {
    //     // 在庫不足の商品があれば、カートの個数を調整する
    //     $stockLessItems = $products->filter(function($product) {
    //         $quantity = Stock::where('product_id', $product->id)->sum('quantity');
    //         return $quantity < $product->pivot->quantity;
    //     });
    //     if($stockLessItems->count()) {
    //         $stockLessItems->each(function($product) {
    //             // Cartモデルを使わずともbelongsto で直接更新できるがcollection だとできない。このメソッドの引数自体を変更する必要がある
    //             // User::findOrFail(Auth::id())->products()->updateExistingPivot($product->id, ['quantity' => 3]);
    //             $cart = Cart::where('product_id', $product->id)->where('user_id', Auth::id())->first();
    //             $cart->quantity = Stock::where('product_id', $product->id)->sum('quantity');
    //             $cart->save();
    //         });
    //         return redirect()->route('user.cart.index')
    //             ->with([
    //                 'message' => 'Less than Stock',
    //                 'status' => 'info',
    //             ]);
    //     }

    // }

}
