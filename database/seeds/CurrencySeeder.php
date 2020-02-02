<?php

use Illuminate\Database\Seeder;
use App\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'CNY'],
            ['name' => 'THB'],
            ['name' => 'MOP'],
            ['name' => 'INR'],
            ['name' => 'PHP'],
            ['name' => 'GBP'],
            ['name' => 'EUR'],
            ['name' => 'PDS'],
            ['name' => 'SGD'],
            ['name' => 'AUD'],
            ['name' => 'CAD'],
            ['name' => 'CHF'],
            ['name' => 'MYR'],
            ['name' => 'NZD'],
            ['name' => 'ZAR'],
            ['name' => 'RUB'],
            ['name' => 'LKR'],
            ['name' => 'IDR'],
            ['name' => 'JPY'],
            ['name' => 'KRW'],
            ['name' => 'SAR'],
            ['name' => 'QAR'],
            ['name' => 'OMR'],
            ['name' => 'KWD'],
            ['name' => 'AED'],
            ['name' => 'HKD'],
            ['name' => 'USD'],
        ];
        Currency::insert($data);
    }
}
