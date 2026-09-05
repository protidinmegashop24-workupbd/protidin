<?php

namespace Database\Seeders;

use App\Models\Admin\DollarRate;
use Illuminate\Database\Seeder;

class DollarRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rate = new DollarRate();
        $rate->rate = 0;
        $rate->save();
    }
}
