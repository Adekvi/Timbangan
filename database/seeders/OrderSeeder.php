<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $buyers = ['NIKE', 'JR286', 'ADIDAS', 'PUMA', 'NEW BALANCE'];
        $countries = [
            ['country' => 'USA', 'city' => 'Los Angeles'],
            ['country' => 'CANADA', 'city' => 'Toronto'],
            ['country' => 'JAPAN', 'city' => 'Tokyo'],
            ['country' => 'GERMANY', 'city' => 'Berlin'],
            ['country' => 'AUSTRALIA', 'city' => 'Sydney'],
        ];
        $silhouettes = ['BACKPACK', 'DUFFEL BAG', 'TOTE BAG', 'GYM BAG', 'WAIST BAG'];
        $colors = ['Black / Red', 'Blue / White', 'Green / Yellow', 'Gray / Orange', 'Navy / Silver'];
        $buyMonths = ['Dec’24 Buy 1st', 'Jan’25 Buy 2nd', 'Feb’25 Buy 3rd', 'Mar’25 Buy 4th', 'Apr’25 Buy 5th'];

        for ($i = 1; $i <= 50; $i++) {
            $buyer = $buyers[array_rand($buyers)];
            $countryData = $countries[array_rand($countries)];
            $silhouette = $silhouettes[array_rand($silhouettes)];
            $color = $colors[array_rand($colors)];
            $buyMonth = $buyMonths[array_rand($buyMonths)];

            $poNumber = 'PO' . rand(1000, 9999);
            $poLine = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $productCode = strtoupper(substr($buyer, 0, 2)) . '-BAG-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $productName = $buyer . ' ' . $silhouette;
            $kj = 'KJ' . rand(24, 25) . strtoupper(substr($buyer, 0, 2)) . str_pad(rand(10, 999), 3, '0', STR_PAD_LEFT);
            $qty = rand(100, 1000);
            $price = rand(10, 20) + (rand(0, 99) / 100); // contoh harga acak
            $documentDate = now()->addDays(rand(10, 60))->format('Y-m-d');
            $gac = now()->addDays(rand(40, 90))->format('Y-m-d');

            $cari = "$buyer $buyMonth $poNumber $poLine $productCode $productName $color $kj $qty $price {$countryData['country']} {$countryData['city']} $silhouette";

            DB::table('v_all_ordersheet_plus_caris')->insert([
                'Order_code' => "$buyer-$poNumber-$poLine",
                'Buyer' => $buyer,
                'BuyMonth' => $buyMonth,
                'PurchaseOrderNumber' => $poNumber,
                'POLineItemNumber' => $poLine,
                'TradingCoPONumber' => null,
                'ProductCode' => $productCode,
                'ProductName' => $productName,
                'ColorDescription' => $color,
                'KJ' => $kj,
                'MOC' => 0,
                'Qty' => $qty,
                'ActualFOB' => $price,
                'DestinationCountry' => $countryData['country'],
                'FinalDestination' => $countryData['city'],
                'GAC' => $gac,
                'DocumentDate' => $documentDate,
                'SilhouetteDescription' => $silhouette,
                'cari' => $cari,
                'status' => 'Null',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        }
    }
}
