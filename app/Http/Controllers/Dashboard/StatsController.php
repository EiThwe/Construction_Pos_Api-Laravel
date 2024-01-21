<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\Gate;

class StatsController extends Controller
{
    public function getStats()
    {
        if (!Gate::allows("checkPermission", "manager")) return response()->json(["message" => "လုပ်ပိုင်ခွင့်မရှိပါ"], 403);

        $staff_count = User::count();
        $customer_count = Customer::count();
        $product_count = Product::count();
        $voucher_count = Voucher::count();

        return response()->json(["data" => [
            [
                "count" =>  $staff_count,
                "label" => "ဝန်ထမ်းအရေအတွက်",
                "icon" => "fa-solid:users",
                "link" => "/users/staff/list"
            ],
            [
                "count" =>  $customer_count,
                "label" => "ဝယ်ယူသူအရေအတွက်",
                "icon" => "ri:customer-service-fill",
                "link" => "/customers/list"
            ],
            [
                "count" =>  $product_count,
                "label" => "ပစ္စည်းအရေအတွက်",
                "icon" => "ic:outline-construction",
                "link" => "/products/list"
            ],
            [
                "count" =>  $voucher_count,
                "label" => "ဘောက်ချာအရေအတွက်",
                "icon" => "mdi:voucher",
                "link" => "/overviews/vouchers"
            ],

        ]]);
    }
}
