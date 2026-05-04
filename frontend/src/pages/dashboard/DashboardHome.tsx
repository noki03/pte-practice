import { Link } from 'react-router-dom'
import { useProgress } from '@/hooks/useAttempts'
import { useAuthStore } from '@/stores/authStore'
import { SkillRadarChart } from '@/components/scoring/SkillRadarChart'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Skeleton } from '@/components/ui/skeleton'
import { SKILL_COLORS } from '@/lib/constants'
import { scoreToColor } from '@/lib/utils'

export function DashboardHome() {
  const { user }                      = useAuthStore()
  const { data: progress, isLoading } = useProgress()
  const firstName                     = user?.name?.split(' ')[0] ?? 'there'

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold">Welcome back, {firstName}!</h1>
        <p className="mt-1 text-sm text-muted-fg">Here&apos;s your PTE progress at a glance.</p>
      </div>

      <div className="grid gap-4 md:grid-cols-2">
        <Card>
          <CardHeader><CardTitle className="text-base">Skill Radar</CardTitle></CardHeader>
          <CardContent>
            {isLoading ? (
              <Skeleton className="h-64 w-full" />
            ) : progress && progress.length > 0 ? (
              <SkillRadarChart progress={progress} />
            ) : (
              <div className="flex h-64 flex-col items-center justify-center text-sm text-muted-fg">
                <p>No attempts yet.</p>
                <p className="mt-1">Complete your first task to see your radar!</p>
              </div>
            )}
          </CardContent>
        </Card>

        <div className="space-y-3">
          {isLoading
            ? Array.from({ length: 4 }, (_, i) => <Skeleton key={i} className="h-20" />)
            : (progress ?? []).map((p) => (
                <Card key={p.skill_slug}>
                  <CardContent className="flex items-center justify-between pb-4 pt-4">
                    <div className="flex items-center gap-2">
                      <div
                        className="h-3 w-3 rounded-full"
                        style={{ backgroundColor: SKILL_COLORS[p.skill_slug] ?? '#6366f1' }}
                      />
                      <span className="font-medium capitalize">{p.skill}</span>
                    </div>
                    <div className="text-right">
                      <p className={`text-xl font-bold tabular-nums ${scoreToColor(p.current_score)}`}>
                        {Math.round(p.current_score)}
                      </p>
                      <p className="text-xs text-muted-fg">{p.attempts_count} attempts</p>
                    </div>
                  </CardContent>
                </Card>
              ))}

          {!isLoading && (!progress || progress.length === 0) && (
            <div className="flex h-full min-h-40 items-center justify-center rounded-lg border border-dashed border-border text-sm text-muted-fg">
              Skill scores will appear here after your first attempt.
            </div>
          )}
        </div>
      </div>

      <div className="flex gap-3">
        <Button asChild>
          <Link to="/practice">Start Practicing</Link>
        </Button>
      </div>
    </div>
  )
}
