<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Product;

class Products extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Product::create([
            'id' => '1',
            'in_stock' => '31',
            'price' => 698.88,
            'price_after' => 698.88,
            'created_at' => Carbon::now(),
            'name' => 'Samsung Galaxy S9',
            'description' => 'A brand new, sealed Lilac Purple Verizon Global Unlocked Galaxy S9 by Samsung. This is an upgrade. Clean ESN and activation ready.',
        ]);
         Product::create([
            'id' => '2',
            'in_stock' => '31',
            'price' => 675.88,
            'price_after' => 675.88,
            'created_at' => Carbon::now(),
            'name' => 'Google Pixel 2 XL',
            'description' => 'New condition • No returns, but backed by eBay Money back guarantee',
        ]);
         Product::create([
            'id' => '3',
            'in_stock' => '31',
            'price' => 159.99,
            'price_after' => 159.99,
            'created_at' => Carbon::now(),
            'name' => 'LG V10 H900',
            'description' => 'NETWORK Technology GSM. Protection Corning Gorilla Glass 4. MISC Colors Space Black, Luxe White, Modern Beige, Ocean Blue, Opal Blue. SAR EU 0.59 W/kg (head).',
        ]);
         Product::create([
            'id' => '4',
            'in_stock' => '31',
            'price' => 68.00,
            'has_offer' => 1,
            'price_after' => 50.99,
            'name' => 'Huawei Elate',
            'description' => 'Cricket Wireless - Huawei Elate. New Sealed Huawei Elate Smartphone.',
        ]);
         Product::create([
            'id' => '5',
            'in_stock' => '31',
            'price' => 129.99,
            'has_offer' => 1,
            'price_after' => 100.99,
            'name' => 'HTC One M10',
            'description' => 'The device is in good cosmetic condition and will show minor scratches and/or scuff marks.',
        ]);
        Product::create([
            'id' => '6',
            'in_stock' => '31',
            'price' => 983.00,
            'has_offer' => 1,
            'price_after' => 900.99,
            'name' => 'Apple iPhone X',
            'description' => 'GSM & CDMA FACTORY UNLOCKED! WORKS WORLDWIDE! FACTORY UNLOCKED. iPhone x 64gb. iPhone 8 64gb. iPhone 8 64gb. iPhone X with iOS 11.',

        ]);
    }
}
