interface RecordingTimerProps {
  elapsedMs:   number
  remainingMs: number
  maxMs:       number
}

function pad(n: number) {
  return String(n).padStart(2, '0')
}

function msToDisplay(ms: number) {
  const total = Math.ceil(ms / 1000)
  const m = Math.floor(total / 60)
  const s = total % 60
  return `${pad(m)}:${pad(s)}`
}

export function RecordingTimer({ elapsedMs, remainingMs, maxMs }: RecordingTimerProps) {
  const progress = Math.min(100, (elapsedMs / maxMs) * 100)
  const isWarning = remainingMs < 10_000

  return (
    <div className="flex flex-col items-center gap-2">
      <div className={`font-mono text-2xl font-bold tabular-nums ${isWarning ? 'text-destructive' : 'text-foreground'}`}>
        {msToDisplay(remainingMs)}
      </div>
      <div className="relative h-1.5 w-48 overflow-hidden rounded-full bg-secondary">
        <div
          className={`h-full transition-all duration-100 ${isWarning ? 'bg-destructive' : 'bg-primary'}`}
          style={{ width: `${progress}%` }}
        />
      </div>
      <p className="text-xs text-muted-fg">
        {msToDisplay(elapsedMs)} elapsed
      </p>
    </div>
  )
}
