<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SubscribeRequest;
use App\Http\Requests\PaymentRequest;
use App\Models\Price;
use App\Models\User;
use Stripe\Plan;
use Stripe\Product;
use Laravel\Cashier\Cashier;
use Stripe\Stripe;
use Stripe\Charge;

class StripeController extends Controller
{
    /**
     * 決済ページ表示
     */
    public function pay()
    {
        return view('single_charges');
    }

    /**
     * stripeを利用して決済
     */
    public function paid(PaymentRequest $request)
    {
        $request->user()->charge(
            $request->amount,
            $request->paymentMethodId
        );

        return back()->with('sent', 'お支払いが完了しました。');
    }
}
