<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherRecord;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        function yearSeeding($year, $month)
        {
            for ($m = 1; $m <= $month; $m++) {
                $startDate = Carbon::createFromDate($year, $m, 1);
                $endDate = Carbon::createFromDate($year, $m, 1)->endOfMonth();

                $numberOfDays = $startDate->diffInDays($endDate) + 1;
                $monthly_total_cash = 0;
                $monthly_total_tax = 0;
                $monthly_total_vouchers = 0;

                for ($d = 1; $d <= $numberOfDays; $d++) {
                    $total_vouchers = rand(10, 15);

                    for ($i = 0; $i < $total_vouchers; $i++) {
                        $voucher_records = [];
                        for ($j = 0; $j < 3; $j++) {
                            $productId = rand(1, 8);
                            $product = Product::find($productId);

                            $quantity = rand(1, 10);

                            $voucher_records[] = [
                                "product_id" => $productId,
                                "price" => $product->primary_price,
                                "quantity" => $quantity,
                                "cost" => $product->primary_price * $quantity,
                            ];
                        };

                        $date = Carbon::create($year, $m, $d)->setTime(rand(9, 16), rand(0, 59), rand(0, 59));
                        $total = 0;
                        $item_count = 0;
                        foreach ($voucher_records as $record) {
                            $total += $record["quantity"] * $record["price"];
                            $item_count += $record["quantity"];
                        };
                        $tax = $total * 0.05;
                        $net_total = $total + $tax;
                        $voucher = [
                            "voucher_number" => Voucher::generateVoucherNumber(),
                            "cost" => $total,
                            "profit" => 300,
                            "pay_amount" => $total,
                            "change" => 0,
                            "debt_amount" => 0,
                            "promotion_amount" => 0,
                            "item_count" => $item_count,
                            "user_id" => 1,
                            "created_at" => $date,
                            "updated_at" => $date,
                        ];
                        $vouchers[] = $voucher;

                        $store_voucher = Voucher::create($voucher);

                        $records = [];
                        foreach ($voucher_records as $record) {
                            $records[] = [
                                "voucher_id" => $store_voucher->id,
                                "product_id" => $record["product_id"],
                                "cost" => $record["price"],
                                "quantity" => $record["quantity"],
                                "cost" => $record["cost"],
                                "unit_id" => $product->primary_unit_id,
                                "created_at" => $date,
                                "updated_at" => $date
                            ];
                        }

                        VoucherRecord::insert($records);
                    }

                    // $total_cash = array_reduce($vouchers, fn ($pv, $cv) => $pv + $cv["net_total"], 0);
                    // $total_tax = array_reduce($vouchers, fn ($pv, $cv) => $pv + $cv["tax"], 0);

                    // $monthly_total_cash += $total_cash;
                    // $monthly_total_tax += $total_tax;
                    // $monthly_total_vouchers += $total_vouchers;

                    // SaleRecord::insert([
                    //     "total_cash" => $total_cash,
                    //     "total_tax" => $total_tax,
                    //     "total_net_total" => $total_cash + $total_tax,
                    //     "total_vouchers" => $total_vouchers,
                    //     "status" => "daily",
                    //     "user_id" => rand(1, 10),
                    //     "created_at" => $date,
                    //     "updated_at" => $date,
                    // ]);

                    if ($d == $numberOfDays) {
                        // SaleRecord::insert([
                        //     "total_cash" => $monthly_total_cash,
                        //     "total_tax" => $monthly_total_tax,
                        //     "total_net_total" => $monthly_total_cash + $monthly_total_tax,
                        //     "total_vouchers" => $monthly_total_vouchers,
                        //     "status" => "monthly",
                        //     "user_id" => rand(1, 10),
                        //     "created_at" => $date,
                        //     "updated_at" => $date,
                        // ]);
                    }
                }

                $vouchers = [];
            }
        }


        function currentMonth()
        {
            $numberOfDays = intval(Carbon::now()->day);
            $monthly_total_cash = 0;
            $monthly_total_tax = 0;
            $monthly_total_vouchers = 0;

            for ($d = 1; $d < $numberOfDays; $d++) {
                $total_vouchers = rand(10, 15);

                for ($i = 0; $i < $total_vouchers; $i++) {
                    $voucher_records = [];
                    for ($j = 0; $j < 3; $j++) {
                        $productId = rand(1, 8);
                        $product = Product::find($productId);

                        $quantity = rand(1, 10);

                        $voucher_records[] = [
                            "product_id" => $productId,
                            "price" => $product->primary_price,
                            "quantity" => $quantity,
                            "cost" => $product->primary_price * $quantity,
                        ];
                    };

                    $date = Carbon::create(2023, 10, $d)->setTime(rand(9, 16), rand(0, 59), rand(0, 59));
                    $total = 0;
                    $item_count = 0;
                    foreach ($voucher_records as $record) {
                        $total += $record["quantity"] * $record["price"];
                        $item_count += $record["quantity"];
                    };
                    $tax = $total * 0.05;
                    $net_total = $total + $tax;
                    $voucher = [
                        "voucher_number" => Voucher::generateVoucherNumber(),
                        "cost" => $total,
                        "profit" => 300,
                        "pay_amount" => $total,
                        "change" => 0,
                        "debt_amount" => 0,
                        "promotion_amount" => 0,
                        "item_count" => $item_count,
                        "user_id" => 1,
                        "created_at" => $date,
                        "updated_at" => $date,
                    ];
                    $vouchers[] = $voucher;

                    $store_voucher = Voucher::create($voucher);

                    $records = [];
                    foreach ($voucher_records as $record) {
                        $records[] = [
                            "voucher_id" => $store_voucher->id,
                            "product_id" => $record["product_id"],
                            "cost" => $record["price"],
                            "quantity" => $record["quantity"],
                            "cost" => $record["cost"],
                            "unit_id" => $product->primary_unit_id,
                            "created_at" => $date,
                            "updated_at" => $date
                        ];
                    }

                    VoucherRecord::insert($records);
                }


                // $total_cash = array_reduce($vouchers, fn ($pv, $cv) => $pv + $cv["net_total"], 0);
                // $total_tax = array_reduce($vouchers, fn ($pv, $cv) => $pv + $cv["tax"], 0);

                // $monthly_total_cash += $total_cash;
                // $monthly_total_tax += $total_tax;
                // $monthly_total_vouchers += $total_vouchers;

                // SaleRecord::insert([
                //     "total_cash" => $total_cash,
                //     "total_tax" => $total_tax,
                //     "total_net_total" => $total_cash + $total_tax,
                //     "total_vouchers" => $total_vouchers,
                //     "status" => "daily",
                //     "user_id" => rand(1, 10),
                //     "created_at" => $date,
                //     "updated_at" => $date,
                // ]);

                $vouchers = [];
            }
        }

        // firstYear();
        // yearSeeding(2022, 12);
        yearSeeding(2023, 12);
        currentMonth();
    }
}
