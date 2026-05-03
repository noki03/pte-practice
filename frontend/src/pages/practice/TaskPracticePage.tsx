import { useEffect, useRef, useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { useTask } from '@/hooks/useTasks'
import { useCreateAttempt, useUploadAudio, useSubmitAttempt } from '@/hooks/useAttempts'
import { ReadAloud } from '@/components/tasks/question-types/ReadAloud'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Skeleton } from '@/components/ui/skeleton'

type Phase = 'recording' | 'reviewing' | 'submitting'

export function TaskPracticePage() {
  const { taskUlid }  = useParams<{ taskUlid: string }>()
  const navigate      = useNavigate()

  const { data: task, isLoading: taskLoading } = useTask(taskUlid!)

  const createAttempt  = useCreateAttempt()
  const uploadAudio    = useUploadAudio()
  const submitAttempt  = useSubmitAttempt()

  const [attemptId, setAttemptId]   = useState<string | null>(null)
  const [captured, setCaptured]     = useState<{ blob: Blob; durationMs: number } | null>(null)
  const [phase, setPhase]           = useState<Phase>('recording')
  const creatingRef                 = useRef(false)

  useEffect(() => {
    if (task && !creatingRef.current) {
      creatingRef.current = true
      createAttempt.mutate(
        { taskId: task.id },
        { onSuccess: (data) => setAttemptId(data.id) },
      )
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [task])

  const handleAudioReady = (blob: Blob, durationMs: number) => {
    setCaptured({ blob, durationMs })
    setPhase('reviewing')
  }

  const handleReRecord = () => {
    setCaptured(null)
    setPhase('recording')
  }

  const handleSubmit = () => {
    if (!attemptId || !captured) return
    setPhase('submitting')
    uploadAudio.mutate(
      { ulid: attemptId, blob: captured.blob, durationMs: captured.durationMs },
      {
        onSuccess: () =>
          submitAttempt.mutate(attemptId, {
            onSuccess: () => navigate(`/results/${attemptId}`),
            onError:   () => setPhase('reviewing'),
          }),
        onError: () => setPhase('reviewing'),
      },
    )
  }

  if (taskLoading) {
    return (
      <div className="max-w-2xl mx-auto space-y-4">
        <Skeleton className="h-8 w-64" />
        <Skeleton className="h-48 w-full" />
        <Skeleton className="h-32 w-full" />
      </div>
    )
  }

  if (!task) {
    return (
      <p className="text-sm text-destructive">Task not found.</p>
    )
  }

  if (createAttempt.isPending || !attemptId) {
    return (
      <div className="flex items-center justify-center py-16">
        <p className="text-sm text-muted-fg">Preparing your task…</p>
      </div>
    )
  }

  return (
    <div className="max-w-2xl mx-auto space-y-4">
      {task.question_type === 'read_aloud' ? (
        <ReadAloud
          task={task}
          attemptId={attemptId}
          onAudioReady={handleAudioReady}
        />
      ) : (
        <Card>
          <CardContent className="pt-6">
            <p className="text-sm text-muted-fg">
              Question type <strong>{task.question_type}</strong> is not yet available in this version.
            </p>
          </CardContent>
        </Card>
      )}

      {phase === 'reviewing' && captured && (
        <Card>
          <CardContent className="flex justify-end gap-3 pb-4 pt-4">
            <Button variant="outline" onClick={handleReRecord}>
              Re-record
            </Button>
            <Button
              onClick={handleSubmit}
              disabled={uploadAudio.isPending || submitAttempt.isPending}
            >
              {uploadAudio.isPending || submitAttempt.isPending ? 'Submitting…' : 'Submit Answer'}
            </Button>
          </CardContent>
        </Card>
      )}

      {(uploadAudio.isError || submitAttempt.isError) && (
        <p className="text-center text-sm text-destructive">
          Something went wrong — please try again.
        </p>
      )}
    </div>
  )
}
