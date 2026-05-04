import { Link, useParams } from 'react-router-dom'
import { useAttempt } from '@/hooks/useAttempts'
import { ScoreBreakdown } from '@/components/scoring/ScoreBreakdown'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Skeleton } from '@/components/ui/skeleton'
import { QUESTION_TYPE_LABELS } from '@/lib/constants'

const STATUS_VARIANT: Record<string, 'default' | 'secondary' | 'destructive' | 'outline'> = {
  scored:      'default',
  submitted:   'secondary',
  in_progress: 'secondary',
  failed:      'destructive',
}

export function SessionResultsPage() {
  const { attemptUlid }           = useParams<{ attemptUlid: string }>()
  const { data: attempt, isLoading } = useAttempt(attemptUlid!)

  if (isLoading || !attempt) {
    return (
      <div className="max-w-lg mx-auto space-y-4">
        <Skeleton className="h-8 w-48" />
        <Skeleton className="h-32 w-full" />
        <Skeleton className="h-48 w-full" />
      </div>
    )
  }

  const typeLabel = attempt.task
    ? (QUESTION_TYPE_LABELS[attempt.task.question_type] ?? attempt.task.question_type)
    : ''

  return (
    <div className="max-w-lg mx-auto space-y-6">
      <div>
        <h1 className="text-2xl font-bold">Results</h1>
        <div className="mt-1 flex items-center gap-2">
          {typeLabel && <p className="text-sm text-muted-fg">{typeLabel}</p>}
          <Badge variant={STATUS_VARIANT[attempt.status] ?? 'outline'}>
            {attempt.status}
          </Badge>
        </div>
      </div>

      {attempt.status === 'scored' ? (
        <ScoreBreakdown attempt={attempt} />
      ) : (
        <div className="rounded-lg border border-dashed border-border p-8 text-center text-sm text-muted-fg">
          {attempt.status === 'submitted' || attempt.status === 'in_progress'
            ? 'Scoring in progress — refresh in a moment.'
            : 'This attempt could not be scored.'}
        </div>
      )}

      <div className="flex flex-wrap gap-3 pt-2">
        <Button variant="outline" asChild>
          <Link to="/practice">Practice another task</Link>
        </Button>
        <Button asChild>
          <Link to="/dashboard">Go to Dashboard</Link>
        </Button>
      </div>
    </div>
  )
}
