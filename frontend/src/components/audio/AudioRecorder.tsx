import { Mic, MicOff, Square, RotateCcw } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { WaveformVisualizer } from './WaveformVisualizer'
import { RecordingTimer } from './RecordingTimer'
import type { UsePteRecorderReturn } from '@/hooks/usePteRecorder'

interface AudioRecorderProps extends UsePteRecorderReturn {
  maxDurationMs: number
  className?:    string
}

const STATE_LABELS: Record<string, string> = {
  idle:                 'Ready to record',
  requesting_permission: 'Requesting microphone…',
  permission_denied:    'Microphone access denied',
  preparing:            'Preparing…',
  recording:            'Recording',
  stopping:             'Stopping…',
  stopped:              'Recording complete',
  error:                'An error occurred',
}

export function AudioRecorder({
  state,
  audioLevel,
  elapsedMs,
  remainingMs,
  maxDurationMs,
  start,
  stop,
  reset,
  className,
}: AudioRecorderProps) {
  const isRecording = state === 'recording'
  const isPreparing = state === 'preparing'
  const isStopped   = state === 'stopped'
  const isBusy      = state === 'requesting_permission' || state === 'stopping'

  return (
    <div className={`flex flex-col items-center gap-4 ${className ?? ''}`}>
      <WaveformVisualizer
        audioLevel={audioLevel}
        isActive={isRecording}
      />

      {(isRecording || isPreparing) && (
        <RecordingTimer
          elapsedMs={elapsedMs}
          remainingMs={remainingMs}
          maxMs={maxDurationMs}
        />
      )}

      <p className="text-sm text-muted-fg">{STATE_LABELS[state] ?? state}</p>

      <div className="flex gap-3">
        {(state === 'idle' || state === 'error' || state === 'permission_denied') && (
          <Button onClick={start} disabled={isBusy} className="gap-2">
            <Mic className="h-4 w-4" />
            Start Recording
          </Button>
        )}

        {(isRecording || isPreparing) && (
          <Button variant="destructive" onClick={stop} className="gap-2">
            <Square className="h-4 w-4" />
            Stop
          </Button>
        )}

        {isStopped && (
          <Button variant="outline" onClick={reset} className="gap-2">
            <RotateCcw className="h-4 w-4" />
            Re-record
          </Button>
        )}

        {state === 'permission_denied' && (
          <p className="text-xs text-destructive text-center max-w-xs">
            Please allow microphone access in your browser settings and try again.
          </p>
        )}
      </div>

      {isStopped && (
        <div className="flex items-center gap-2 text-xs text-muted-fg">
          <MicOff className="h-3 w-3" />
          Audio captured — ready to submit
        </div>
      )}
    </div>
  )
}
