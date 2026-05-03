<?php

namespace App\Enums;

enum QuestionType: string
{
    // Speaking
    case ReadAloud            = 'read_aloud';
    case RepeatSentence       = 'repeat_sentence';
    case DescribeImage        = 'describe_image';
    case ReTellLecture        = 're_tell_lecture';
    case AnswerShortQuestion  = 'answer_short_question';

    // Writing
    case SummarizeWrittenText = 'summarize_written_text';
    case WriteEssay           = 'write_essay';

    // Reading
    case ReadingWritingFillInBlanks = 'reading_writing_fill_in_blanks';
    case MultipleChoiceMultipleReading = 'multiple_choice_multiple_reading';
    case ReorderParagraphs    = 're_order_paragraphs';
    case ReadingFillInBlanks  = 'reading_fill_in_blanks';
    case MultipleChoiceSingleReading = 'multiple_choice_single_reading';

    // Listening
    case SummarizeSpokenText  = 'summarize_spoken_text';
    case MultipleChoiceMultipleListening = 'multiple_choice_multiple_listening';
    case FillInBlanksListening = 'fill_in_blanks_listening';
    case HighlightCorrectSummary = 'highlight_correct_summary';
    case MultipleChoiceSingleListening = 'multiple_choice_single_listening';
    case SelectMissingWord    = 'select_missing_word';
    case HighlightIncorrectWords = 'highlight_incorrect_words';
    case WriteFromDictation   = 'write_from_dictation';

    public function section(): string
    {
        return match($this) {
            self::ReadAloud, self::RepeatSentence, self::DescribeImage,
            self::ReTellLecture, self::AnswerShortQuestion => 'speaking',

            self::SummarizeWrittenText, self::WriteEssay => 'writing',

            self::ReadingWritingFillInBlanks, self::MultipleChoiceMultipleReading,
            self::ReorderParagraphs, self::ReadingFillInBlanks,
            self::MultipleChoiceSingleReading => 'reading',

            self::SummarizeSpokenText, self::MultipleChoiceMultipleListening,
            self::FillInBlanksListening, self::HighlightCorrectSummary,
            self::MultipleChoiceSingleListening, self::SelectMissingWord,
            self::HighlightIncorrectWords, self::WriteFromDictation => 'listening',
        };
    }

    public function requiresAudioRecording(): bool
    {
        return in_array($this, [
            self::ReadAloud,
            self::RepeatSentence,
            self::DescribeImage,
            self::ReTellLecture,
            self::AnswerShortQuestion,
        ]);
    }

    public function requiresAudioPlayback(): bool
    {
        return in_array($this, [
            self::RepeatSentence,
            self::ReTellLecture,
            self::AnswerShortQuestion,
            self::SummarizeSpokenText,
            self::MultipleChoiceMultipleListening,
            self::FillInBlanksListening,
            self::HighlightCorrectSummary,
            self::MultipleChoiceSingleListening,
            self::SelectMissingWord,
            self::HighlightIncorrectWords,
            self::WriteFromDictation,
        ]);
    }

    public function label(): string
    {
        return match($this) {
            self::ReadAloud                         => 'Read Aloud',
            self::RepeatSentence                    => 'Repeat Sentence',
            self::DescribeImage                     => 'Describe Image',
            self::ReTellLecture                     => 'Re-tell Lecture',
            self::AnswerShortQuestion               => 'Answer Short Question',
            self::SummarizeWrittenText              => 'Summarize Written Text',
            self::WriteEssay                        => 'Write Essay',
            self::ReadingWritingFillInBlanks        => 'Reading & Writing: Fill in the Blanks',
            self::MultipleChoiceMultipleReading     => 'Multiple Choice (Multiple) - Reading',
            self::ReorderParagraphs                 => 'Re-order Paragraphs',
            self::ReadingFillInBlanks               => 'Reading: Fill in the Blanks',
            self::MultipleChoiceSingleReading       => 'Multiple Choice (Single) - Reading',
            self::SummarizeSpokenText               => 'Summarize Spoken Text',
            self::MultipleChoiceMultipleListening   => 'Multiple Choice (Multiple) - Listening',
            self::FillInBlanksListening             => 'Fill in the Blanks (Listening)',
            self::HighlightCorrectSummary           => 'Highlight Correct Summary',
            self::MultipleChoiceSingleListening     => 'Multiple Choice (Single) - Listening',
            self::SelectMissingWord                 => 'Select Missing Word',
            self::HighlightIncorrectWords           => 'Highlight Incorrect Words',
            self::WriteFromDictation                => 'Write from Dictation',
        };
    }
}
