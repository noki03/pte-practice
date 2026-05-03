<?php

namespace Database\Seeders;

use App\Enums\SkillType;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SkillType::cases() as $skill) {
            Skill::updateOrCreate(
                ['slug' => $skill->value],
                [
                    'name'          => $skill->label(),
                    'color_hex'     => $skill->colorHex(),
                    'max_score'     => 90,
                    'display_order' => $skill->displayOrder(),
                ]
            );
        }
    }
}
