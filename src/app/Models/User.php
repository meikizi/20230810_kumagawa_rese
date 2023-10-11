<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use Stripe\Plan;
use Stripe\Product;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified',
        'email_verify_token',
        'phone_number',
        'postcode',
        'address',
        'birthday',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Shops関連付け
     * 多対多
     */
    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class)
            ->withPivot('date', 'time', 'number');
    }

    public function bookMarks()
    {
        return $this->hasMany(BookMark::class);
    }

    public function shopReviews()
    {
        return $this->hasMany(ShopReview::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)
            ->withPivot('shop_id');
    }

    public function userHasRole($role_name){
        foreach($this->roles as $role){
            if($role_name==$role->name){
                return true;
            }
                return false;
        }
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * ユーザーに紐づいているサブスクリプションを返す
     */
    public function products()
    {
        $products = [];
        foreach ($this->subscriptions()->get() as $subscription) {
            $priceId = $subscription->stripe_plan;

            // price id から plan を取得
            $plan = Plan::retrieve($priceId);
            // prod id から product を取得
            $product = Product::retrieve($plan->product);

            // dashboardで設定したメタデータを取得
            $localName           = $product->metadata->localName;
            $product->cancelled  = $this->subscription($localName)->cancelled();

            $products[] = $product;
        }

        return $products;
    }

}
