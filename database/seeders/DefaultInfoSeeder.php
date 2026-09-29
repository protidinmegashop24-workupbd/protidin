<?php

namespace Database\Seeders;

use App\Models\Admin\JobFee;
use App\Models\Admin\MainWallet;
use App\Models\Admin\WithdrawFee;
use Illuminate\Database\Seeder;

class DefaultInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fee = new JobFee();
        $fee->fee = 0;
        $fee->save();

        $fee = new WithdrawFee();
        $fee->fee = 0;
        $fee->save();

        $fee = new MainWallet();
        $fee->amount = 0;
        $fee->save();
    }
}
