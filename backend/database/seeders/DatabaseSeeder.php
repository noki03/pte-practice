<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SkillSeeder::class,       // must run first (scoring_rules FK)
            ScoringRuleSeeder::class, // depends on skills
            TaskSeeder::class,        // independent
        ]);
    }
}
