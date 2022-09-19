<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
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
        Student::factory(100)->create()->each(function ($student) {
            User::factory(1, [
                'student_id' => $student->id
            ])->create();
        });
        Teacher::factory(20)->create()->each(function ($teacher) {
            User::factory(1, [
                'teacher_id' => $teacher->id
            ])->create();
        });
        $this->call([
            YearGroupSeeder::class,
            FormSeeder::class,
        ]);
    }
}
