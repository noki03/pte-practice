import { apiClient } from './client'
import type { Attempt, SkillProgress } from '@/types/attempt'
import type { ApiResponse } from '@/types/api'

export const attemptsApi = {
  create: (taskId: string, sessionId?: string) =>
    apiClient.post<ApiResponse<Attempt>>('/v1/attempts', { task_id: taskId, session_id: sessionId }),

  update: (ulid: string, data: { response_text?: string; response_data?: unknown }) =>
    apiClient.put<ApiResponse<Attempt>>(`/v1/attempts/${ulid}`, data),

  uploadAudio: (ulid: string, blob: Blob, durationMs: number) => {
    const form = new FormData()
    form.append('audio', blob, `attempt_${ulid}.webm`)
    form.append('duration_ms', String(durationMs))
    return apiClient.post<ApiResponse<Attempt>>(`/v1/attempts/${ulid}/audio`, form, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
  },

  submit: (ulid: string) =>
    apiClient.post<ApiResponse<Attempt>>(`/v1/attempts/${ulid}/submit`),

  get: (ulid: string) =>
    apiClient.get<ApiResponse<Attempt>>(`/v1/attempts/${ulid}`),

  progress: () =>
    apiClient.get<{ data: SkillProgress[] }>('/v1/progress'),
}
