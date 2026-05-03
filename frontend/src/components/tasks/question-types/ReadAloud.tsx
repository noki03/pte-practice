import { useEffect, useRef } from 'react'
import { usePteRecorder } from '@/hooks/usePteRecorder'
import { AudioRecorder } from '@/components/audio/AudioRecorder'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Progress } from '@/components/ui/progress'
import type { Task } from '@/types/task'

interface ReadAloudProps {
  task:          Task
  attemptId:     string
  onAudioReady?: (blob: Blob, durationMs: number) => void
}

const PREP_MS   = 30_000
const RECORD_MS = 40_000

export function ReadAloud({ task, attemptId, onAudioReady }: ReadAloudProps) {
  const prepMs   = (task.metadata?.preparation_time_s  ?? 30) * 1000
  const recordMs = (task.metadata?.response_time_s     ?? 40) * 1000

  const recorder = usePteRecorder({
    id:               attemptId,
    preparationTimeMs: prepMs,
    maxDurationMs:     recordMs,
    onComplete:        onAudioReady,
  })

  // Track preparation countdown
  const prepIntervalRef = useRef<ReturnType<typeof setInterval> | null>(null)

  useEffect(() => {
    if (recorder.state === 'preparing') {
      prepIntervalRef.current = setInterval(() => {}, 100)
    } else if (prepIntervalRef.current) {
      clearInterval(prepIntervalRef.current)
    }
    return () => {
      if (prepIntervalRef.current) clearInterval(prepIntervalRef.current)
    }
  }, [recorder.state])

  const prepProgress = recorder.state === 'preparing'
    ? Math.min(100, (recorder.elapsedMs / prepMs) * 100)
    : 0

  return (
    <div className="space-y-4">
      <Card>
        <CardHeader className="pb-3">
          <div className="flex items-start justify-between gap-4">
            <div>
              <CardTitle className="text-base">{task.title}</CardTitle>
              <CardDescription className="mt-1">
                Read the following text aloud. You will have{' '}
                {task.metadata?.preparation_time_s ?? 30}s to prepare, then{' '}
                {task.metadata?.response_time_s ?? 40}s to record.
              </CardDescription>
            </div>
            <Badge variant="secondary">Read Aloud</Badge>
          </div>
        </CardHeader>
        <CardContent>
          <p className="text-base leading-relaxed">{task.content}</p>
          {task.metadata?.word_count && (
            <p className="mt-2 text-xs text-muted-fg">
              {task.metadata.word_count} words · Est. {task.estimated_duration_s}s
            </p>
          )}
        </CardContent>
      </Card>

      {recorder.state === 'preparing' && (
        <Card>
          <CardContent className="pt-4 space-y-2">
            <div className="flex justify-between text-sm">
              <span className="text-muted-fg">Preparation time</span>
              <span className="font-mono tabular-nums">
                {Math.max(0, Math.ceil((prepMs - recorder.elapsedMs) / 1000))}s
              </span>
            </div>
            <Progress value={prepProgress} />
            <p className="text-xs text-muted-fg text-center">
              Read the passage carefully before recording begins
            </p>
          </CardContent>
        </Card>
      )}

      <Card>
        <CardContent className="pt-6 pb-6">
          <AudioRecorder
            {...recorder}
            maxDurationMs={recordMs}
          />
        </CardContent>
      </Card>
    </div>
  )
}
