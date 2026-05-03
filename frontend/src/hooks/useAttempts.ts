import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { attemptsApi } from '@/api/attempts'
import { queryKeys } from '@/lib/queryClient'

export function useAttempt(ulid: string) {
  return useQuery({
    queryKey: queryKeys.attempts.detail(ulid),
    queryFn:  () => attemptsApi.get(ulid).then((r) => r.data.data),
    enabled:  !!ulid,
  })
}

export function useCreateAttempt() {
  return useMutation({
    mutationFn: ({ taskId, sessionId }: { taskId: string; sessionId?: string }) =>
      attemptsApi.create(taskId, sessionId).then((r) => r.data.data),
  })
}

export function useUploadAudio() {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: ({ ulid, blob, durationMs }: { ulid: string; blob: Blob; durationMs: number }) =>
      attemptsApi.uploadAudio(ulid, blob, durationMs).then((r) => r.data.data),
    onSuccess: (_, { ulid }) => {
      qc.invalidateQueries({ queryKey: queryKeys.attempts.detail(ulid) })
    },
  })
}

export function useSubmitAttempt() {
  const qc = useQueryClient()
  return useMutation({
    mutationFn: (ulid: string) =>
      attemptsApi.submit(ulid).then((r) => r.data.data),
    onSuccess: (data) => {
      qc.setQueryData(queryKeys.attempts.detail(data.id), data)
      qc.invalidateQueries({ queryKey: queryKeys.progress.user() })
    },
  })
}

export function useProgress() {
  return useQuery({
    queryKey: queryKeys.progress.user(),
    queryFn:  () => attemptsApi.progress().then((r) => r.data.data),
  })
}
