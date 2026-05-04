import { useQuery } from '@tanstack/react-query'
import { tasksApi } from '@/api/tasks'
import { queryKeys } from '@/lib/queryClient'
import type { TaskFilters } from '@/types/task'

export function useTasks(filters: TaskFilters = {}) {
  return useQuery({
    queryKey: queryKeys.tasks.filtered(filters as Record<string, unknown>),
    queryFn:  () => tasksApi.list(filters).then((r) => r.data),
  })
}

export function useTask(ulid: string) {
  return useQuery({
    queryKey: queryKeys.tasks.detail(ulid),
    queryFn:  () => tasksApi.get(ulid).then((r) => r.data.data),
    enabled:  !!ulid,
  })
}
