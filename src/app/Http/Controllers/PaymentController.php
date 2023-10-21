<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;

class PaymentController extends Controller
{
    /**
     * 決済ページ表示
     */
    public function pay()
    {
        return view('payment');
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
