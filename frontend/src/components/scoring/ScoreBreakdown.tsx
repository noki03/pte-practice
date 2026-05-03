import type { Attempt, SkillScore } from '@/types/attempt'
import { SKILL_COLORS } from '@/lib/constants'
import { scoreToColor } from '@/lib/utils'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'

interface ScoreBreakdownProps {
  attempt: Attempt
}

function DimensionRow({ label, value, max = 5 }: { label: string; value: number; max?: number }) {
  const pct = Math.round((value / max) * 100)
  return (
    <div className="flex items-center gap-3">
      <span className="w-28 text-xs capitalize text-muted-fg">{label}</span>
      <div className="flex-1 h-1.5 rounded-full bg-secondary overflow-hidden">
        <div className="h-full bg-primary rounded-full" style={{ width: `${pct}%` }} />
      </div>
      <span className="w-12 text-right text-xs font-mono">{value}/{max}</span>
    </div>
  )
}

function SkillCard({ score }: { score: SkillScore }) {
  const color = SKILL_COLORS[score.skill_slug] ?? '#6366f1'
  return (
    <div className="rounded-md border border-border p-4 space-y-3">
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-2">
          <div className="h-3 w-3 rounded-full" style={{ backgroundColor: color }} />
          <span className="font-medium capitalize">{score.skill}</span>
          {score.is_primary && <Badge variant="secondary" className="text-[10px]">primary</Badge>}
        </div>
        <span className={`text-lg font-bold tabular-nums ${scoreToColor(score.weighted_score)}`}>
          {Math.round(score.weighted_score)}
        </span>
      </div>
      <div className="space-y-1.5">
        {Object.entries(score.dimension_scores).map(([dim, val]) => (
          <DimensionRow key={dim} label={dim} value={val} />
        ))}
      </div>
    </div>
  )
}

export function ScoreBreakdown({ attempt }: ScoreBreakdownProps) {
  const overallColor = scoreToColor(attempt.normalized_score ?? 0)

  return (
    <div className="space-y-4">
      <Card>
        <CardHeader className="pb-2">
          <CardTitle className="text-base">Overall Score</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="flex items-baseline gap-2">
            <span className={`text-4xl font-bold tabular-nums ${overallColor}`}>
              {Math.round(attempt.normalized_score ?? 0)}
            </span>
            <span className="text-muted-fg text-sm">/ 90</span>
          </div>
        </CardContent>
      </Card>

      {attempt.skill_scores && attempt.skill_scores.length > 0 && (
        <div className="space-y-3">
          <h3 className="text-sm font-semibold text-muted-fg uppercase tracking-wider">
            Skill Breakdown
          </h3>
          {attempt.skill_scores.map((s) => (
            <SkillCard key={s.skill_slug} score={s} />
          ))}
        </div>
      )}
    </div>
  )
}
