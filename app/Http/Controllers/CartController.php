<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Voucher;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get cart from session
     */
    private function getCart()
    {
        return session()->get('cart', []);
    }

    /**
     * Save cart to session
     */
    private function saveCart($cart)
    {
        session()->put('cart', $cart);
    }

    /**
     * Show cart page
     */
    public function index()
    {
        $cart = $this->getCart();
        
        // Group items by canteen
        $cartByCanteen = [];
        foreach ($cart as $item) {
            $canteenId = $item['canteen_id'];
            if (!isset($cartByCanteen[$canteenId])) {
                $cartByCanteen[$canteenId] = [
                    'canteen' => Menu::find($item['menu_id'])->canteen,
                    'items' => [],
                    'total' => 0,
                ];
            }
            $cartByCanteen[$canteenId]['items'][] = $item;
            $cartByCanteen[$canteenId]['total'] += $item['price'] * $item['quantity'];
        }

        $grandTotal = array_sum(array_map(fn($c) => $c['total'], $cartByCanteen));

        return view('cart', compact('cartByCanteen', 'grandTotal', 'cart'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $cart = $this->getCart();

        // Check if item already in cart
        $existingKey = null;
        foreach ($cart as $key => $item) {
            if ($item['menu_id'] == $menu->id) {
                $existingKey = $key;
                break;
            }
        }

        if ($existingKey !== null) {
            // Update quantity
            $cart[$existingKey]['quantity'] += $validated['quantity'];
        } else {
            // Add new item
            $cart[] = [
                'menu_id' => $menu->id,
                'canteen_id' => $menu->canteen_id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $validated['quantity'],
            ];
        }

        $this->saveCart($cart);

        return redirect('/cart')->with('success', 'Item ditambahkan ke keranjang');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $menuId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = $this->getCart();

        foreach ($cart as $key => $item) {
            if ($item['menu_id'] == $menuId) {
                if ($validated['quantity'] <= 0) {
                    unset($cart[$key]);
                } else {
                    $cart[$key]['quantity'] = $validated['quantity'];
                }
                break;
            }
        }

        $this->saveCart(array_values($cart)); // Re-index array

        return redirect('/cart')->with('success', 'Keranjang diupdate');
    }

    /**
     * Remove item from cart
     */
    public function remove($menuId)
    {
        $cart = $this->getCart();

        $cart = array_filter($cart, fn($item) => $item['menu_id'] != $menuId);

        $this->saveCart(array_values($cart));

        return redirect('/cart')->with('success', 'Item dihapus dari keranjang');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect('/cart')->with('success', 'Keranjang dikosongkan');
    }

    /**
     * Get cart summary via AJAX
     */
    public function summary()
    {
        $cart = $this->getCart();
        
        $cartByCanteen = [];
        foreach ($cart as $item) {
            $canteenId = $item['canteen_id'];
            if (!isset($cartByCanteen[$canteenId])) {
                $cartByCanteen[$canteenId] = [
                    'items' => [],
                    'total' => 0,
                ];
            }
            $cartByCanteen[$canteenId]['items'][] = $item;
            $cartByCanteen[$canteenId]['total'] += $item['price'] * $item['quantity'];
        }

        $grandTotal = array_sum(array_map(fn($c) => $c['total'], $cartByCanteen));
        $itemCount = count($cart);

        return response()->json([
            'success' => true,
            'itemCount' => $itemCount,
            'cartByCanteen' => $cartByCanteen,
            'grandTotal' => $grandTotal,
        ]);
    }

    /**
     * Checkout - process selected items (checkbox system)
     * Allows user to select specific canteens to checkout
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'selected_canteens' => 'required|array|min:1',
            'selected_canteens.*' => 'integer',
            'voucher_code' => 'nullable|string',
        ]);

        $cart = $this->getCart();
        $selectedCanteens = $validated['selected_canteens'];

        // Filter cart items for selected canteens
        $checkoutItems = array_filter(
            $cart,
            fn($item) => in_array($item['canteen_id'], $selectedCanteens)
        );

        if (empty($checkoutItems)) {
            return redirect('/cart')->with('error', 'Pilih minimal 1 kantin untuk checkout');
        }

        // Calculate total
        $grandTotal = 0;
        $cartByCanteen = [];

        foreach ($checkoutItems as $item) {
            $canteenId = $item['canteen_id'];
            $itemTotal = $item['price'] * $item['quantity'];
            
            $grandTotal += $itemTotal;
            
            if (!isset($cartByCanteen[$canteenId])) {
                $cartByCanteen[$canteenId] = [
                    'total' => 0,
                    'items' => [],
                ];
            }
            $cartByCanteen[$canteenId]['total'] += $itemTotal;
            $cartByCanteen[$canteenId]['items'][] = $item;
        }

        // Apply voucher if provided
        $discount = 0;
        if ($validated['voucher_code']) {
            $voucher = Voucher::where('code', $validated['voucher_code'])->first();
            
            if ($voucher && $voucher->valid_from <= now() && $voucher->valid_to >= now()) {
                if ($voucher->discount_percentage) {
                    $discount = ($voucher->discount_percentage / 100) * $grandTotal;
                } else {
                    $discount = $voucher->discount_amount;
                }
                $grandTotal -= $discount;
            }
        }

        // Store checkout data in session for payment processing
        session()->put('checkout', [
            'cart_by_canteen' => $cartByCanteen,
            'grand_total' => $grandTotal,
            'discount' => $discount,
            'voucher_code' => $validated['voucher_code'],
            'checkout_items' => array_values($checkoutItems),
        ]);

        return redirect('/checkout')->with('success', 'Siap checkout');
    }
}
