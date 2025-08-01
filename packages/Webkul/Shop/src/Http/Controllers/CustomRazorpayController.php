<?php

namespace Webkul\Shop\Http\Controllers;

use Wontonee\Razorpay\Http\Controllers\RazorpayController;
use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Razorpay\Api\Api;

class CustomRazorpayController extends RazorpayController
{
    // You can override only the needed methods
    public function paymentRedirect(Request $request)
    {
        $cart = Cart::getCart();
        $billingAddress = $cart->billing_address;
        $shipping_rate = $cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0;
        $discount_amount = $cart->discount_amount;

        $total_amount = ($cart->sub_total + $cart->tax_total + $shipping_rate) - $discount_amount;

        $gatewayLogo = core()->getConfigData('sales.payment_methods.razorpay.gateway_logo');
        $siteLogo = core()->getCurrentChannel()->logo_url;

        $paymentLogo = $gatewayLogo
            ? asset('storage/' . $gatewayLogo)
            : ($siteLogo ?? '');

        $themeColor = core()->getConfigData('sales.payment_methods.razorpay.theme_color') ?: '#F37254';

        // ✅ Create Razorpay Order using SDK
        $key = core()->getConfigData('sales.payment_methods.razorpay.key_id');
        $secret = core()->getConfigData('sales.payment_methods.razorpay.secret');
        $api = new Api($key, $secret);
        $amountInPaise = (int) round($total_amount * 100);
        try {
            $razorpayOrder = $api->order->create([
                'receipt' => 'Receipt no. ' . $cart->id,
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'notes' => [
                    'address' => $billingAddress->address,
                    'merchant_order_id' => $cart->id,
                ]
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create Razorpay order: ' . $e->getMessage());
            return redirect()->route('shop.checkout.cart.index');
        }

        $razorpayOrderId = $razorpayOrder['id'];
        $request->session()->put('razorpay_order_id', $razorpayOrderId);

        $data = [
            "key" => $key,
            "amount" => $amountInPaise,
            "name" => $billingAddress->name,
            "description" => "RazorPay payment collection for the order - " . $cart->id,
            "image" => $paymentLogo,
            "prefill" => [
                "name" => $billingAddress->name,
                "email" => $billingAddress->email,
                "contact" => $billingAddress->phone,
            ],
            "notes" => [
                "address" => $billingAddress->address,
                "merchant_order_id" => $cart->id,
            ],
            "theme" => [
                "color" => $themeColor
            ],
            "order_id" => $razorpayOrderId,
            "callback_url" => route('razorpay.callback')
        ];

        $json = json_encode($data);

        return view('razorpay::razorpay-redirect')->with(compact('data', 'json'));
    }
}
