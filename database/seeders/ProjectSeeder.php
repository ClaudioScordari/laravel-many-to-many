<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Models
use App\Models\Project;
use App\Models\Type;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::truncate();

        for ($i=0; $i < 10; $i++) { 
            $project = Project::create([
                'name' => fake()->word(),
                'description' => fake()->text(),
                'type_id' => Type::inRandomOrder()->first()->id
            ]);
        }
    }
}
