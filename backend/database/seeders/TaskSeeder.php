<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = $this->getReadAloudTasks();

        foreach ($tasks as $task) {
            Task::updateOrCreate(
                ['title' => $task['title'], 'question_type' => $task['question_type']],
                array_merge($task, ['ulid' => Str::ulid()])
            );
        }
    }

    private function getReadAloudTasks(): array
    {
        return [
            [
                'question_type'       => 'read_aloud',
                'section'             => 'speaking',
                'title'               => 'Climate Change and Its Effects',
                'instructions'        => 'Look at the text below. In 30 seconds, you must read this text aloud as naturally and clearly as possible. You have 40 seconds to read aloud.',
                'content'             => 'Climate change refers to long-term shifts in temperatures and weather patterns. These shifts may be natural, but since the 1800s, human activities have been the main driver of climate change, primarily due to the burning of fossil fuels like coal, oil and gas. Burning fossil fuels generates greenhouse gas emissions that act like a blanket wrapped around the Earth, trapping the sun\'s heat and raising temperatures.',
                'difficulty'          => 2,
                'estimated_duration_s' => 70,
                'is_active'           => true,
                'metadata'            => [
                    'preparation_time_s'  => 30,
                    'response_time_s'     => 40,
                    'word_count'          => 73,
                    'scoring_criteria'    => ['content', 'fluency', 'pronunciation'],
                ],
            ],
            [
                'question_type'       => 'read_aloud',
                'section'             => 'speaking',
                'title'               => 'The Role of Technology in Education',
                'instructions'        => 'Look at the text below. In 30 seconds, you must read this text aloud as naturally and clearly as possible. You have 40 seconds to read aloud.',
                'content'             => 'Technology has transformed education by making learning more accessible and engaging. Digital tools and online platforms enable students to access a wealth of information at any time, collaborate with peers globally, and learn at their own pace. Teachers, too, benefit from technology, as it allows them to personalize instruction, track student progress more efficiently, and introduce interactive learning experiences that were previously impossible.',
                'difficulty'          => 3,
                'estimated_duration_s' => 70,
                'is_active'           => true,
                'metadata'            => [
                    'preparation_time_s'  => 30,
                    'response_time_s'     => 40,
                    'word_count'          => 74,
                    'scoring_criteria'    => ['content', 'fluency', 'pronunciation'],
                ],
            ],
            [
                'question_type'       => 'read_aloud',
                'section'             => 'speaking',
                'title'               => 'Biodiversity and Ecosystem Services',
                'instructions'        => 'Look at the text below. In 30 seconds, you must read this text aloud as naturally and clearly as possible. You have 40 seconds to read aloud.',
                'content'             => 'Biodiversity encompasses the variety of life on Earth, including all species of plants, animals, fungi, and microorganisms, as well as the genetic differences within species and the ecosystems they form. Healthy ecosystems provide essential services to humanity, such as clean air and water, pollination of crops, climate regulation, and nutrient cycling. The loss of biodiversity threatens these services and, consequently, human wellbeing.',
                'difficulty'          => 4,
                'estimated_duration_s' => 70,
                'is_active'           => true,
                'metadata'            => [
                    'preparation_time_s'  => 30,
                    'response_time_s'     => 40,
                    'word_count'          => 72,
                    'scoring_criteria'    => ['content', 'fluency', 'pronunciation'],
                ],
            ],
            [
                'question_type'       => 'read_aloud',
                'section'             => 'speaking',
                'title'               => 'Urban Migration Trends',
                'instructions'        => 'Look at the text below. In 30 seconds, you must read this text aloud as naturally and clearly as possible. You have 40 seconds to read aloud.',
                'content'             => 'Urbanisation is one of the defining trends of the twenty-first century. As rural populations move to cities in search of better economic opportunities and social services, urban areas are expanding rapidly. This demographic shift presents both opportunities and challenges. Cities can drive economic growth and innovation, but they also face issues of congestion, pollution, housing shortages, and the strain on public infrastructure.',
                'difficulty'          => 3,
                'estimated_duration_s' => 70,
                'is_active'           => true,
                'metadata'            => [
                    'preparation_time_s'  => 30,
                    'response_time_s'     => 40,
                    'word_count'          => 72,
                    'scoring_criteria'    => ['content', 'fluency', 'pronunciation'],
                ],
            ],
            [
                'question_type'       => 'read_aloud',
                'section'             => 'speaking',
                'title'               => 'Artificial Intelligence in Healthcare',
                'instructions'        => 'Look at the text below. In 30 seconds, you must read this text aloud as naturally and clearly as possible. You have 40 seconds to read aloud.',
                'content'             => 'Artificial intelligence is increasingly being applied in healthcare to improve diagnostics, streamline administrative processes, and personalise treatment plans. Machine learning algorithms can analyse medical images with remarkable accuracy, often identifying conditions that human clinicians might overlook. Despite these advances, the integration of AI into healthcare raises important questions about data privacy, accountability, and the potential displacement of healthcare workers.',
                'difficulty'          => 5,
                'estimated_duration_s' => 70,
                'is_active'           => true,
                'metadata'            => [
                    'preparation_time_s'  => 30,
                    'response_time_s'     => 40,
                    'word_count'          => 71,
                    'scoring_criteria'    => ['content', 'fluency', 'pronunciation'],
                ],
            ],
        ];
    }
}
