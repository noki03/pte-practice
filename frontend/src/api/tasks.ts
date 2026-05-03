import { apiClient } from './client'
import type { Task, TaskFilters } from '@/types/task'
import type { PaginatedResponse, ApiResponse } from '@/types/api'

export const tasksApi = {
  list: (filters: TaskFilters = {}) =>
    apiClient.get<PaginatedResponse<Task>>('/v1/tasks', { params: filters }),

  get: (ulid: string) =>
    apiClient.get<ApiResponse<Task>>(`/v1/tasks/${ulid}`),
}
