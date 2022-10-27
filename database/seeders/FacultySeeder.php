<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        Faculty::create([
            'name'        => 'Faculty of Business and Social Sciences',
            'description' => $faker->paragraph(4),
            'code'        => 'SAM'
        ]);
        Faculty::create([
            'name'        => 'Faculty of Engineering',
            'description' => $faker->paragraph(4),
            'code'        => 'TEK'
        ]);
        Faculty::create([
            'name'        => 'Faculty of Health Sciences',
            'description' => $faker->paragraph(4),
            'code'        => 'SUN'
        ]);
        Faculty::create([
            'name'        => 'Faculty of Humanities',
            'description' => $faker->paragraph(4),
            'code'        => 'HUM'
        ]);
        Faculty::create([
            'name'        => 'Faculty of Science',
            'description' => $faker->paragraph(4),
            'code'        => 'NAT'
        ]);
    }
}
