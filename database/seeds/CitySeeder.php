<?php

use Illuminate\Database\Seeder;
use App\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $file = file(base_path('data/cities.csv'));
        $data = [];

        foreach ($file as $line) {
            $data[] = str_getcsv($line, '|');
        }

        if (2 > count($data)) {
            return;
        }

        $map = array_shift($data);
        foreach ($data as $index => $_data) {
            $data[$index] = array_combine($map, $_data);
        }

        City::insert($data);
    }
}
