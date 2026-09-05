<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionBank;

class QuestionBankSeeder extends Seeder
{
    public function run()
    {
        // Islamic Questions
        QuestionBank::insert([
            [
                'topic' => 'islamic',
                'question' => 'ইসলামের প্রথম খলিফা কে ছিলেন?',
                'options' => json_encode(['হযরত উমর (রা)','হযরত আবু বকর (রা)','হযরত আলী (রা)','হযরত উসমান (রা)']),
                'correct_option' => 'হযরত আবু বকর (রা)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic' => 'islamic',
                'question' => 'কোরআন কত বছরে নাযিল হয়?',
                'options' => json_encode(['১০ বছর','১৫ বছর','২৩ বছর','২৫ বছর']),
                'correct_option' => '২৩ বছর',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // General Knowledge
        QuestionBank::insert([
            [
                'topic' => 'general',
                'question' => 'বাংলাদেশের রাজধানী কি?',
                'options' => json_encode(['চট্টগ্রাম','ঢাকা','রাজশাহী','সিলেট']),
                'correct_option' => 'ঢাকা',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic' => 'general',
                'question' => 'বিশ্বের বৃহত্তম মহাসাগর কোনটি?',
                'options' => json_encode(['আটলান্টিক','প্যাসিফিক','ইন্ডিয়ান','আর্কটিক']),
                'correct_option' => 'প্যাসিফিক',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
