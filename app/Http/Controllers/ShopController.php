<?php

namespace App\Http\Controllers;

use App\Models\ShopProduct;
use App\Models\ShopCategory;
use App\Models\ShopOrder;
use App\Models\ShopOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index()
    {
        $categories = ShopCategory::with('products')->orderBy('order')->get();
        return view('shop.index', compact('categories'));
    }

    public function showProduct(ShopProduct $product)
    {
        return view('shop.product', compact('product'));
    }

    public function addToCart(Request $request, ShopProduct $product)
    {
        $this->middleware('auth');

        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Produkt zum Warenkorb hinzugefügt');
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('shop.cart', compact('cart', 'total'));
    }

    public function checkout(Request $request)
    {
        $this->middleware('auth');

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Warenkorb ist leer');
        }

        DB::beginTransaction();

        try {
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $order = ShopOrder::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                ShopOrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            session()->forget('cart');
            DB::commit();

            return redirect()->route('shop.orders')->with('success', 'Bestellung erfolgreich aufgegeben');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Fehler bei der Bestellung');
        }
    }

    public function orders()
    {
        $this->middleware('auth');
        $orders = auth()->user()->shopOrders()->with('items.product')->latest()->get();
        return view('shop.orders', compact('orders'));
    }
}

