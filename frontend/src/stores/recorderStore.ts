import { create } from 'zustand'

interface RecorderStore {
  activeRecorderId: string | null
  isRecording: boolean
  setActiveRecorder: (id: string | null) => void
}

export const useRecorderStore = create<RecorderStore>()((set) => ({
  activeRecorderId: null,
  isRecording:      false,
  setActiveRecorder: (id) => set({ activeRecorderId: id, isRecording: id !== null }),
}))
