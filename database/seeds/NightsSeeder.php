<?php

use Illuminate\Database\Seeder;

use App\Master;
use App\MasterValue;

class NightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master = Master::where('slug', 'nights')->first();
        if (empty($master)) {
            return;
        }
        $data = [];
        for ($i = 1; $i <= 21; $i++) {
            $data[] = [
                'master_id' => $master->id,
                'value' => $i,
                'is_active' => 1,
            ];
        }
        MasterValue::insert($data);
    }
}
