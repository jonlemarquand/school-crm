<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YearGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 7; $i <= 13; $i++) {
            $year_to_use = $i + 4;
            DB::table('year_groups')->insert([
                'school_year' => $i,
                'start_date' => date('Y-m-d', strtotime('2022-09-01 -'.$year_to_use.' year')),
                'end_date' => date('Y-m-d', strtotime('2023-08-31 -'.$year_to_use.' year')),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
