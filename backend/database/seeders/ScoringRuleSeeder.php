<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\ScoringRule;
use Illuminate\Database\Seeder;

class ScoringRuleSeeder extends Seeder
{
    public function run(): void
    {
        $skills = Skill::pluck('id', 'slug');

        $rules = $this->getRules($skills);

        foreach ($rules as $rule) {
            ScoringRule::updateOrCreate(
                [
                    'question_type'    => $rule['question_type'],
                    'skill_id'         => $rule['skill_id'],
                    'dimension'        => $rule['dimension'],
                ],
                $rule
            );
        }
    }

    private function getRules(object $skills): array
    {
        $speaking  = $skills['speaking'];
        $writing   = $skills['writing'];
        $reading   = $skills['reading'];
        $listening = $skills['listening'];

        return [
            // ── Read Aloud ────────────────────────────────────────────────
            // Primary: Speaking (content + fluency + pronunciation)
            ['question_type' => 'read_aloud', 'skill_id' => $speaking, 'dimension' => 'content',       'weight' => 0.3333, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'read_aloud', 'skill_id' => $speaking, 'dimension' => 'fluency',       'weight' => 0.3333, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'read_aloud', 'skill_id' => $speaking, 'dimension' => 'pronunciation', 'weight' => 0.3334, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            // Integrated: Reading (content only)
            ['question_type' => 'read_aloud', 'skill_id' => $reading,  'dimension' => 'content',       'weight' => 1.0000, 'max_points' => 5, 'is_primary_skill' => false, 'scoring_method' => 'partial_credit', 'rubric_config' => null],

            // ── Repeat Sentence ───────────────────────────────────────────
            ['question_type' => 'repeat_sentence', 'skill_id' => $speaking, 'dimension' => 'content',       'weight' => 0.3333, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'repeat_sentence', 'skill_id' => $speaking, 'dimension' => 'fluency',       'weight' => 0.3333, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'repeat_sentence', 'skill_id' => $speaking, 'dimension' => 'pronunciation', 'weight' => 0.3334, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'repeat_sentence', 'skill_id' => $listening, 'dimension' => 'content',      'weight' => 1.0000, 'max_points' => 5, 'is_primary_skill' => false, 'scoring_method' => 'partial_credit', 'rubric_config' => null],

            // ── Describe Image ────────────────────────────────────────────
            ['question_type' => 'describe_image', 'skill_id' => $speaking, 'dimension' => 'content',       'weight' => 0.3333, 'max_points' => 5, 'is_primary_skill' => true, 'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'describe_image', 'skill_id' => $speaking, 'dimension' => 'fluency',       'weight' => 0.3333, 'max_points' => 5, 'is_primary_skill' => true, 'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'describe_image', 'skill_id' => $speaking, 'dimension' => 'pronunciation', 'weight' => 0.3334, 'max_points' => 5, 'is_primary_skill' => true, 'scoring_method' => 'rubric', 'rubric_config' => null],

            // ── Re-tell Lecture ───────────────────────────────────────────
            ['question_type' => 're_tell_lecture', 'skill_id' => $speaking, 'dimension' => 'content',       'weight' => 0.3333, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 're_tell_lecture', 'skill_id' => $speaking, 'dimension' => 'fluency',       'weight' => 0.3333, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 're_tell_lecture', 'skill_id' => $speaking, 'dimension' => 'pronunciation', 'weight' => 0.3334, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 're_tell_lecture', 'skill_id' => $listening, 'dimension' => 'content',      'weight' => 1.0000, 'max_points' => 5, 'is_primary_skill' => false, 'scoring_method' => 'partial_credit', 'rubric_config' => null],

            // ── Answer Short Question ─────────────────────────────────────
            ['question_type' => 'answer_short_question', 'skill_id' => $speaking, 'dimension' => 'content', 'weight' => 1.0000, 'max_points' => 1, 'is_primary_skill' => true,  'scoring_method' => 'exact_match', 'rubric_config' => null],
            ['question_type' => 'answer_short_question', 'skill_id' => $listening, 'dimension' => 'content','weight' => 1.0000, 'max_points' => 1, 'is_primary_skill' => false, 'scoring_method' => 'exact_match', 'rubric_config' => null],

            // ── Summarize Written Text ────────────────────────────────────
            ['question_type' => 'summarize_written_text', 'skill_id' => $writing, 'dimension' => 'content',    'weight' => 0.25, 'max_points' => 2, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'summarize_written_text', 'skill_id' => $writing, 'dimension' => 'form',       'weight' => 0.25, 'max_points' => 1, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'summarize_written_text', 'skill_id' => $writing, 'dimension' => 'grammar',    'weight' => 0.25, 'max_points' => 2, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'summarize_written_text', 'skill_id' => $writing, 'dimension' => 'vocabulary', 'weight' => 0.25, 'max_points' => 2, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'summarize_written_text', 'skill_id' => $reading, 'dimension' => 'content',    'weight' => 1.00, 'max_points' => 2, 'is_primary_skill' => false, 'scoring_method' => 'rubric', 'rubric_config' => null],

            // ── Write Essay ───────────────────────────────────────────────
            ['question_type' => 'write_essay', 'skill_id' => $writing, 'dimension' => 'content',    'weight' => 0.20, 'max_points' => 3, 'is_primary_skill' => true, 'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'write_essay', 'skill_id' => $writing, 'dimension' => 'form',       'weight' => 0.20, 'max_points' => 2, 'is_primary_skill' => true, 'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'write_essay', 'skill_id' => $writing, 'dimension' => 'grammar',    'weight' => 0.20, 'max_points' => 2, 'is_primary_skill' => true, 'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'write_essay', 'skill_id' => $writing, 'dimension' => 'vocabulary', 'weight' => 0.20, 'max_points' => 2, 'is_primary_skill' => true, 'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'write_essay', 'skill_id' => $writing, 'dimension' => 'spelling',   'weight' => 0.20, 'max_points' => 2, 'is_primary_skill' => true, 'scoring_method' => 'rubric', 'rubric_config' => null],

            // ── Write from Dictation ──────────────────────────────────────
            ['question_type' => 'write_from_dictation', 'skill_id' => $listening, 'dimension' => 'content', 'weight' => 0.50, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'partial_credit', 'rubric_config' => null],
            ['question_type' => 'write_from_dictation', 'skill_id' => $writing,   'dimension' => 'spelling', 'weight' => 0.50, 'max_points' => 5, 'is_primary_skill' => false, 'scoring_method' => 'partial_credit', 'rubric_config' => null],

            // ── Fill in Blanks (Reading) ───────────────────────────────────
            ['question_type' => 'reading_fill_in_blanks', 'skill_id' => $reading, 'dimension' => 'content', 'weight' => 1.00, 'max_points' => 5, 'is_primary_skill' => true, 'scoring_method' => 'partial_credit', 'rubric_config' => null],

            // ── Multiple Choice (Reading) ─────────────────────────────────
            ['question_type' => 'multiple_choice_single_reading',   'skill_id' => $reading, 'dimension' => 'content', 'weight' => 1.00, 'max_points' => 1, 'is_primary_skill' => true, 'scoring_method' => 'exact_match', 'rubric_config' => null],
            ['question_type' => 'multiple_choice_multiple_reading', 'skill_id' => $reading, 'dimension' => 'content', 'weight' => 1.00, 'max_points' => 5, 'is_primary_skill' => true, 'scoring_method' => 'partial_credit', 'rubric_config' => null],

            // ── Re-order Paragraphs ───────────────────────────────────────
            ['question_type' => 're_order_paragraphs', 'skill_id' => $reading, 'dimension' => 'content', 'weight' => 1.00, 'max_points' => 5, 'is_primary_skill' => true, 'scoring_method' => 'partial_credit', 'rubric_config' => null],

            // ── Summarize Spoken Text ─────────────────────────────────────
            ['question_type' => 'summarize_spoken_text', 'skill_id' => $writing,   'dimension' => 'content',    'weight' => 0.25, 'max_points' => 2, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'summarize_spoken_text', 'skill_id' => $writing,   'dimension' => 'form',       'weight' => 0.25, 'max_points' => 1, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'summarize_spoken_text', 'skill_id' => $writing,   'dimension' => 'grammar',    'weight' => 0.25, 'max_points' => 2, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'summarize_spoken_text', 'skill_id' => $writing,   'dimension' => 'vocabulary', 'weight' => 0.25, 'max_points' => 2, 'is_primary_skill' => true,  'scoring_method' => 'rubric', 'rubric_config' => null],
            ['question_type' => 'summarize_spoken_text', 'skill_id' => $listening, 'dimension' => 'content',    'weight' => 1.00, 'max_points' => 2, 'is_primary_skill' => false, 'scoring_method' => 'rubric', 'rubric_config' => null],

            // ── Listening Multiple Choice ─────────────────────────────────
            ['question_type' => 'multiple_choice_single_listening',   'skill_id' => $listening, 'dimension' => 'content', 'weight' => 1.00, 'max_points' => 1, 'is_primary_skill' => true, 'scoring_method' => 'exact_match',    'rubric_config' => null],
            ['question_type' => 'multiple_choice_multiple_listening', 'skill_id' => $listening, 'dimension' => 'content', 'weight' => 1.00, 'max_points' => 5, 'is_primary_skill' => true, 'scoring_method' => 'partial_credit', 'rubric_config' => null],

            // ── Highlight Correct Summary ─────────────────────────────────
            ['question_type' => 'highlight_correct_summary', 'skill_id' => $listening, 'dimension' => 'content', 'weight' => 0.50, 'max_points' => 1, 'is_primary_skill' => true,  'scoring_method' => 'exact_match', 'rubric_config' => null],
            ['question_type' => 'highlight_correct_summary', 'skill_id' => $reading,   'dimension' => 'content', 'weight' => 0.50, 'max_points' => 1, 'is_primary_skill' => false, 'scoring_method' => 'exact_match', 'rubric_config' => null],

            // ── Select Missing Word ───────────────────────────────────────
            ['question_type' => 'select_missing_word', 'skill_id' => $listening, 'dimension' => 'content', 'weight' => 1.00, 'max_points' => 1, 'is_primary_skill' => true, 'scoring_method' => 'exact_match', 'rubric_config' => null],

            // ── Highlight Incorrect Words ─────────────────────────────────
            ['question_type' => 'highlight_incorrect_words', 'skill_id' => $listening, 'dimension' => 'content', 'weight' => 0.50, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'partial_credit', 'rubric_config' => null],
            ['question_type' => 'highlight_incorrect_words', 'skill_id' => $reading,   'dimension' => 'content', 'weight' => 0.50, 'max_points' => 5, 'is_primary_skill' => false, 'scoring_method' => 'partial_credit', 'rubric_config' => null],

            // ── Fill in Blanks (Listening) ────────────────────────────────
            ['question_type' => 'fill_in_blanks_listening', 'skill_id' => $listening, 'dimension' => 'content', 'weight' => 0.50, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'partial_credit', 'rubric_config' => null],
            ['question_type' => 'fill_in_blanks_listening', 'skill_id' => $writing,   'dimension' => 'spelling', 'weight' => 0.50, 'max_points' => 5, 'is_primary_skill' => false, 'scoring_method' => 'partial_credit', 'rubric_config' => null],

            // ── Reading & Writing Fill in Blanks ──────────────────────────
            ['question_type' => 'reading_writing_fill_in_blanks', 'skill_id' => $reading, 'dimension' => 'content', 'weight' => 0.50, 'max_points' => 5, 'is_primary_skill' => true,  'scoring_method' => 'partial_credit', 'rubric_config' => null],
            ['question_type' => 'reading_writing_fill_in_blanks', 'skill_id' => $writing, 'dimension' => 'content', 'weight' => 0.50, 'max_points' => 5, 'is_primary_skill' => false, 'scoring_method' => 'partial_credit', 'rubric_config' => null],
        ];
    }
}
