<?php

use Illuminate\Database\Seeder;

use App\Nationality;

class NationalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require_once base_path() . '/data/countries.php';
        $data = [];
        foreach ($countries as $_country) {
            $data[] = [
                'country_code' => $_country['alpha_2_code'],
                'name' => $_country['nationality'],
            ];
        }
        Nationality::insert($data);
    }
}
