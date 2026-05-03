import { create } from 'zustand'
import type { Task } from '@/types/task'

interface SessionStore {
  sessionId: string | null
  taskQueue: Task[]
  currentTaskIndex: number
  setSession: (sessionId: string, tasks: Task[]) => void
  advanceTask: () => void
  clearSession: () => void
}

export const useSessionStore = create<SessionStore>()((set) => ({
  sessionId:        null,
  taskQueue:        [],
  currentTaskIndex: 0,

  setSession: (sessionId, tasks) => set({ sessionId, taskQueue: tasks, currentTaskIndex: 0 }),
  advanceTask: () => set((s) => ({ currentTaskIndex: s.currentTaskIndex + 1 })),
  clearSession: () => set({ sessionId: null, taskQueue: [], currentTaskIndex: 0 }),
}))
