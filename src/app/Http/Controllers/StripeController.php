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

    // public function subscription()
    // {
    //     $user_id = Auth::id();
    //     $user = User::find($user_id);
    //     return view('subscription',  [
    //         'intent' => $user->createSetupIntent(),
    //         // // 現在のユーザーに紐づいているサブスクリプション
    //         // 'userProducts' => $user->products(),
    //         // // dashboardで作成されているサブスクリプション全件
    //         // 'products' => Price::getAll(),
    //     ]);
    // }

    // public function afterpay(Request $request)
    // {
    //     $user = $request->user();
    //     // $priceId = $request->get('plan');

    //     // またStripe顧客でなければ、新規顧客にする
    //     $stripeCustomer = $user->createOrGetStripeCustomer();

    //     $paymentMethodId = $request->paymentMethodId;

    //     // プランはconfigに設定したbasic_plan_idとする
    //     $plan = config('services.stripe.basic_plan_id');

    //     // 上記のプランと支払方法で、サブスクを新規作成する
    //     $user->newSubscription('default', $plan)
    //         ->create($paymentMethodId);

    //     return back();
    // }

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

        return back();
    }
}
