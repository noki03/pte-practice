import type { AttemptStatus } from './enums'

export interface SkillScore {
  skill: string
  skill_slug: string
  is_primary: boolean
  dimension_scores: Record<string, number>
  raw_score: number
  weighted_score: number
}

export interface Attempt {
  id: string
  status: AttemptStatus
  response_text?: string
  response_data?: Record<string, unknown>
  audio_duration_ms?: number
  audio_recorded_at?: string
  audio_url?: string
  raw_score?: number
  normalized_score?: number
  scoring_metadata?: { dimension_scores?: Record<string, number> }
  skill_scores?: SkillScore[]
  task?: { id: string; title: string; question_type: string }
  started_at: string
  submitted_at?: string
  scored_at?: string
}

export interface User {
  id: string
  name: string
  email: string
  email_verified: boolean
  target_exam_date?: string
  target_score?: number
  timezone: string
  ui_preferences: { dark_mode: boolean; font_size?: string }
  created_at: string
}

export interface SkillProgress {
  skill: string
  skill_slug: string
  color_hex: string
  current_score: number
  attempts_count: number
  last_attempt_at?: string
  score_history: { score: number; date: string }[]
}
