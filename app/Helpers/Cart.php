<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

if (!function_exists('cart')) {
    /**
     * Check has cart
     *
     * @return boolean
     */
    function has_cart(): bool
    {
        return Cookie::has('cart');
    }
}

if (!function_exists('cart_items')) {
    /**
     * Cart items
     *
     * @return \Illuminate\Support\Collection
     */
    function cart_items(): Collection
    {
        $cookie_cart = Cookie::get('cart') ?? '[]';
        $cart = (is_array($cookie_cart)) ? $cookie_cart : json_decode($cookie_cart, true);
        return collect($cart['items'] ?? []);
    }
}

if (!function_exists('cart_shipping')) {
    /**
     * Shipping information
     *
     * @return array
     */
    function cart_shipping(): array
    {
        $cookie_cart = Cookie::get('cart') ?? '[]';
        $cart = (is_array($cookie_cart)) ? $cookie_cart : json_decode($cookie_cart, true);
        return $cart['shipping'] ?? [];
    }
}

if (!function_exists('cart_total')) {
    /**
     * Cart total information
     *
     * @param \Illuminate\Support\Collection|null $carts
     * @param array|null $cartShipping
     * @param boolean $avoidShipping
     * @return array
     */
    function cart_total($carts = null, $cartShipping = null, $avoidShipping = false): array
    {
        if (empty($carts)) {
            $carts = cart_items();
        }

        if (empty($cartShipping) && !$avoidShipping) {
            $cartShipping = cart_shipping();
        }

        $totalPrice = 0;
        $totalDiscount = 0;
        foreach ($carts as $i => $cart) {
            $totalPrice = $totalPrice + $cart['price'] * $cart['qty'];
            $totalDiscount = $totalDiscount + $cart['discount'] * $cart['qty'];
        }
        $subTotal = $totalPrice - $totalDiscount;
        $shippingFee = 0;
        if (is_array($cartShipping) && array_key_exists('cost', $cartShipping)) {
            $shippingFee = floatval($cartShipping['cost'] ?? 0);
        }
        $total = $subTotal + $shippingFee;

        return [
            'total_price' => $totalPrice,
            'total_discount' => $totalDiscount,
            'sub_total' => $subTotal,
            'shipping_fee' => $shippingFee,
            'total' => $total,
            'formated' => [
                'total_price' => 'Rp ' . number_format($totalPrice, 0, ',', '.'),
                'total_discount' => 'Rp ' . number_format($totalDiscount, 0, ',', '.'),
                'sub_total' => 'Rp ' . number_format($subTotal, 0, ',', '.'),
                'shipping_fee' => 'Rp ' . number_format($shippingFee, 0, ',', '.'),
                'total' => 'Rp ' . number_format($total, 0, ',', '.'),
            ]
        ];
    }
}

if (!function_exists('cart_shop')) {
    /**
     * Shop information
     *
     * @return array
     */
    function cart_shop(): array
    {
        $cookie_cart = Cookie::get('cart') ?? '[]';
        $cart = (is_array($cookie_cart)) ? $cookie_cart : json_decode($cookie_cart, true);
        return $cart['shop'] ?? [];
    }
}

if (!function_exists('empty_cart')) {
    /**
     * Do empty cart cookie
     *
     * @return boolean
     */
    function empty_cart(): bool
    {
        if (Cookie::has('cart')) {
            Cookie::queue(Cookie::forget('cart'));
        }

        return true;
    }
}

if (!function_exists('cart_note')) {
    /**
     * Cart note information
     *
     * @return string|null
     */
    function cart_note()
    {
        $cookie_cart = Cookie::get('cart') ?? '[]';
        $cart = (is_array($cookie_cart)) ? $cookie_cart : json_decode($cookie_cart, true);
        return $cart['notes'] ?? null;
    }
}
