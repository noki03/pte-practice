export type QuestionType =
  | 'read_aloud' | 'repeat_sentence' | 'describe_image'
  | 're_tell_lecture' | 'answer_short_question'
  | 'summarize_written_text' | 'write_essay'
  | 'reading_writing_fill_in_blanks' | 'multiple_choice_multiple_reading'
  | 're_order_paragraphs' | 'reading_fill_in_blanks' | 'multiple_choice_single_reading'
  | 'summarize_spoken_text' | 'multiple_choice_multiple_listening'
  | 'fill_in_blanks_listening' | 'highlight_correct_summary'
  | 'multiple_choice_single_listening' | 'select_missing_word'
  | 'highlight_incorrect_words' | 'write_from_dictation'

export type Section = 'speaking' | 'writing' | 'reading' | 'listening'

export type AttemptStatus = 'in_progress' | 'submitted' | 'scored' | 'failed'

export type RecorderState =
  | 'idle'
  | 'requesting_permission'
  | 'permission_denied'
  | 'preparing'
  | 'recording'
  | 'stopping'
  | 'stopped'
  | 'error'
