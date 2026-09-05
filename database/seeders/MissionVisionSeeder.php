<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissionVisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mission_visions')->insert([
            'title' => 'Title',
            'slug' => 'title',
            'details' => 'details'
        ]);
    }
}
