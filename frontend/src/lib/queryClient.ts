import { QueryClient } from '@tanstack/react-query'

export const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime:          60_000,
      gcTime:             300_000,
      retry:              2,
      refetchOnWindowFocus: false,
    },
    mutations: {
      retry: 0,
    },
  },
})

export const queryKeys = {
  tasks: {
    all:      ['tasks'] as const,
    filtered: (filters: Record<string, unknown>) => ['tasks', 'filtered', filters] as const,
    detail:   (ulid: string) => ['tasks', 'detail', ulid] as const,
  },
  attempts: {
    detail:    (ulid: string) => ['attempts', 'detail', ulid] as const,
    bySession: (sessionUlid: string) => ['attempts', 'session', sessionUlid] as const,
  },
  progress: {
    user: () => ['progress', 'user'] as const,
  },
  auth: {
    me: () => ['auth', 'me'] as const,
  },
}
