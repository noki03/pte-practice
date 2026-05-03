import { useCallback, useEffect, useRef, useState } from 'react'
import { useRecorderStore } from '@/stores/recorderStore'
import type { RecorderState } from '@/types/enums'

export interface UsePteRecorderOptions {
  id: string
  preparationTimeMs?: number
  maxDurationMs?: number
  onComplete?: (blob: Blob, durationMs: number) => void
  onError?: (error: Error) => void
}

export interface UsePteRecorderReturn {
  state: RecorderState
  audioLevel: number
  elapsedMs: number
  remainingMs: number
  audioBlob: Blob | null
  start: () => Promise<void>
  stop: () => void
  reset: () => void
}

const PREFERRED_CODECS = [
  'audio/webm;codecs=opus',
  'audio/ogg;codecs=opus',
  'audio/mp4',
  'audio/webm',
]

function getSupportedMimeType(): string {
  for (const codec of PREFERRED_CODECS) {
    if (MediaRecorder.isTypeSupported(codec)) return codec
  }
  return ''
}

export function usePteRecorder({
  id,
  preparationTimeMs = 0,
  maxDurationMs = 40_000,
  onComplete,
  onError,
}: UsePteRecorderOptions): UsePteRecorderReturn {
  const [state, setState]           = useState<RecorderState>('idle')
  const [audioLevel, setAudioLevel] = useState(0)
  const [elapsedMs, setElapsedMs]   = useState(0)
  const [audioBlob, setAudioBlob]   = useState<Blob | null>(null)

  const streamRef       = useRef<MediaStream | null>(null)
  const recorderRef     = useRef<MediaRecorder | null>(null)
  const chunksRef       = useRef<Blob[]>([])
  const audioCtxRef     = useRef<AudioContext | null>(null)
  const analyserRef     = useRef<AnalyserNode | null>(null)
  const animFrameRef    = useRef<number>(0)
  const startTimeRef    = useRef<number>(0)
  const prepTimerRef    = useRef<ReturnType<typeof setTimeout> | null>(null)
  const autoStopRef     = useRef<ReturnType<typeof setTimeout> | null>(null)
  const elapsedIntervalRef = useRef<ReturnType<typeof setInterval> | null>(null)

  const { setActiveRecorder } = useRecorderStore()

  const cleanup = useCallback(() => {
    if (prepTimerRef.current)    clearTimeout(prepTimerRef.current)
    if (autoStopRef.current)     clearTimeout(autoStopRef.current)
    if (elapsedIntervalRef.current) clearInterval(elapsedIntervalRef.current)
    if (animFrameRef.current)    cancelAnimationFrame(animFrameRef.current)

    streamRef.current?.getTracks().forEach((t) => t.stop())
    if (audioCtxRef.current?.state !== 'closed') audioCtxRef.current?.close()

    streamRef.current   = null
    recorderRef.current = null
    audioCtxRef.current = null
    analyserRef.current = null
    chunksRef.current   = []
  }, [])

  useEffect(() => () => { cleanup(); setActiveRecorder(null) }, [cleanup, setActiveRecorder])

  const trackAudioLevel = useCallback(() => {
    if (!analyserRef.current) return
    const data = new Uint8Array(analyserRef.current.frequencyBinCount)
    const tick = () => {
      analyserRef.current?.getByteFrequencyData(data)
      const avg = data.reduce((a, b) => a + b, 0) / data.length
      setAudioLevel(avg / 255)
      animFrameRef.current = requestAnimationFrame(tick)
    }
    animFrameRef.current = requestAnimationFrame(tick)
  }, [])

  const startRecording = useCallback(() => {
    const stream = streamRef.current
    if (!stream) return

    chunksRef.current = []
    const mimeType = getSupportedMimeType()
    const recorder  = new MediaRecorder(stream, mimeType ? { mimeType } : undefined)
    recorderRef.current = recorder

    // Setup Web Audio analyser for waveform
    const audioCtx  = new AudioContext()
    const source    = audioCtx.createMediaStreamSource(stream)
    const analyser  = audioCtx.createAnalyser()
    analyser.fftSize = 256
    source.connect(analyser)
    audioCtxRef.current = audioCtx
    analyserRef.current = analyser
    trackAudioLevel()

    recorder.ondataavailable = (e) => {
      if (e.data.size > 0) chunksRef.current.push(e.data)
    }

    recorder.onstop = () => {
      const durationMs = Date.now() - startTimeRef.current
      const blob = new Blob(chunksRef.current, { type: mimeType || 'audio/webm' })
      setAudioBlob(blob)
      setState('stopped')
      setActiveRecorder(null)
      cancelAnimationFrame(animFrameRef.current)
      setAudioLevel(0)
      if (elapsedIntervalRef.current) clearInterval(elapsedIntervalRef.current)
      onComplete?.(blob, durationMs)
    }

    recorder.start(250) // 250ms timeslice for real-time level data
    startTimeRef.current = Date.now()
    setState('recording')

    elapsedIntervalRef.current = setInterval(() => {
      setElapsedMs(Date.now() - startTimeRef.current)
    }, 100)

    // Auto-stop after maxDurationMs
    autoStopRef.current = setTimeout(() => {
      if (recorderRef.current?.state === 'recording') {
        recorderRef.current.stop()
        setState('stopping')
      }
    }, maxDurationMs)
  }, [maxDurationMs, onComplete, setActiveRecorder, trackAudioLevel])

  const start = useCallback(async () => {
    try {
      setState('requesting_permission')
      const stream = await navigator.mediaDevices.getUserMedia({ audio: true })
      streamRef.current = stream

      setActiveRecorder(id)

      if (preparationTimeMs > 0) {
        setState('preparing')
        setElapsedMs(0)
        prepTimerRef.current = setTimeout(() => startRecording(), preparationTimeMs)
      } else {
        startRecording()
      }
    } catch (err) {
      setState(err instanceof DOMException && err.name === 'NotAllowedError'
        ? 'permission_denied'
        : 'error')
      onError?.(err instanceof Error ? err : new Error(String(err)))
    }
  }, [id, preparationTimeMs, setActiveRecorder, startRecording, onError])

  const stop = useCallback(() => {
    if (prepTimerRef.current) {
      clearTimeout(prepTimerRef.current)
      prepTimerRef.current = null
    }
    if (autoStopRef.current) {
      clearTimeout(autoStopRef.current)
      autoStopRef.current = null
    }
    if (recorderRef.current?.state === 'recording') {
      setState('stopping')
      recorderRef.current.stop()
    }
  }, [])

  const reset = useCallback(() => {
    cleanup()
    setActiveRecorder(null)
    setState('idle')
    setElapsedMs(0)
    setAudioLevel(0)
    setAudioBlob(null)
  }, [cleanup, setActiveRecorder])

  const remainingMs = Math.max(0, maxDurationMs - (state === 'recording' ? elapsedMs : 0))

  return { state, audioLevel, elapsedMs, remainingMs, audioBlob, start, stop, reset }
}
