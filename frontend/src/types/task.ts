import type { QuestionType, Section } from './enums'

export interface TaskMetadata {
  preparation_time_s?: number
  response_time_s?: number
  word_count?: number
  scoring_criteria?: string[]
  image_url?: string
  audio_url?: string
  options?: string[] | string[][]
  correct_answers?: number[]
  is_multiple_select?: boolean
  paragraphs?: { id: string; text: string }[]
  correct_order?: string[]
  text_with_blanks?: string
  answers?: string[]
  min_words?: number
  max_words?: number
}

export interface Task {
  id: string
  question_type: QuestionType
  question_type_label: string
  section: Section
  title: string
  instructions?: string
  content?: string
  difficulty: number
  estimated_duration_s: number
  metadata?: TaskMetadata
  requires_recording: boolean
  requires_playback: boolean
  created_at: string
}

export interface TaskFilters {
  section?: Section
  question_type?: QuestionType
  difficulty?: number
  per_page?: number
  page?: number
}
