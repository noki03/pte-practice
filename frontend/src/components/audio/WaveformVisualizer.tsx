import { cn } from '@/lib/utils'

interface WaveformVisualizerProps {
  audioLevel: number
  isActive:   boolean
  bars?:      number
  className?: string
}

export function WaveformVisualizer({
  audioLevel,
  isActive,
  bars = 20,
  className,
}: WaveformVisualizerProps) {
  return (
    <div className={cn('flex items-center justify-center gap-0.5 h-12', className)}>
      {Array.from({ length: bars }, (_, i) => {
        const center  = bars / 2
        const dist    = Math.abs(i - center) / center
        const base    = (1 - dist * 0.5) * 0.15
        const height  = isActive
          ? base + audioLevel * (1 - dist * 0.4) * 0.85
          : base

        return (
          <div
            key={i}
            className={cn(
              'w-1 rounded-full transition-all duration-75',
              isActive ? 'bg-primary' : 'bg-muted-fg/40',
            )}
            style={{ height: `${Math.max(4, height * 100)}%` }}
          />
        )
      })}
    </div>
  )
}
