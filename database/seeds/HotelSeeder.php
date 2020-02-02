<?php

use Illuminate\Database\Seeder;
use App\Hotel;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = file(base_path('data/hotels.csv'));
        $data = [];

        foreach ($file as $line) {
            $data[] = str_getcsv($line, '|');
        }

        if (2 > count($data)) {
            return;
        }

        $map = array_shift($data);
        $insert_data = [];
        foreach ($data as $index => $_data) {
            $insert_data[$index] = array_combine($map, $_data);
        }

        $insert_data = array_chunk($insert_data, 1000, true);
        foreach ($insert_data as $_data) {
            Hotel::insert($_data);
        }
    }
}
