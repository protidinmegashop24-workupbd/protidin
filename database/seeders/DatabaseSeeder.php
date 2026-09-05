<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(WebsiteSeeder::class);
        $this->call(MissionVisionSeeder::class);
        $this->call(AboutusSeeder::class);
        $this->call(CareerSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(WelcomeBonusSeeder::class);
        $this->call(DollarRateSeeder::class);
        $this->call(DefaultInfoSeeder::class);
    }
}
