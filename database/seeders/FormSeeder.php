<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i >= 20; $i++) {
            DB::table('forms')->insert([
                'teacher_id' => $i,
                'year_group_id' => rand(1,7),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
