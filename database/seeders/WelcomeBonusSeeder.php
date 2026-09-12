<?php

namespace Database\Seeders;

use App\Models\Admin\WelcomeBonus;
use Illuminate\Database\Seeder;

class WelcomeBonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $welcome_bonus = new WelcomeBonus();
        $welcome_bonus->amount = 0;
        $welcome_bonus->save();
    }
}
