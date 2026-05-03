import {
  RadarChart,
  Radar,
  PolarGrid,
  PolarAngleAxis,
  PolarRadiusAxis,
  ResponsiveContainer,
  Tooltip,
} from 'recharts'
import type { SkillProgress } from '@/types/attempt'
import { SKILL_COLORS, PTE_SCORE_MAX } from '@/lib/constants'

interface SkillRadarChartProps {
  progress: SkillProgress[]
  height?:  number
}

export function SkillRadarChart({ progress, height = 280 }: SkillRadarChartProps) {
  const data = progress.map((p) => ({
    skill: p.skill.charAt(0).toUpperCase() + p.skill.slice(1),
    score: Math.round(p.current_score),
    slug:  p.skill_slug,
  }))

  return (
    <ResponsiveContainer width="100%" height={height}>
      <RadarChart data={data} margin={{ top: 10, right: 30, bottom: 10, left: 30 }}>
        <PolarGrid stroke="var(--color-border)" />
        <PolarAngleAxis
          dataKey="skill"
          tick={{ fontSize: 12, fill: 'var(--color-muted-fg)' }}
        />
        <PolarRadiusAxis
          angle={90}
          domain={[0, PTE_SCORE_MAX]}
          tick={{ fontSize: 10, fill: 'var(--color-muted-fg)' }}
          tickCount={4}
        />
        <Radar
          name="Score"
          dataKey="score"
          stroke={SKILL_COLORS.speaking}
          fill={SKILL_COLORS.speaking}
          fillOpacity={0.25}
          dot={{ fill: SKILL_COLORS.speaking, r: 3 }}
        />
        <Tooltip
          formatter={(value: number) => [value, 'Score']}
          contentStyle={{
            background: 'var(--color-card)',
            border: '1px solid var(--color-border)',
            borderRadius: '0.5rem',
            fontSize: 12,
          }}
        />
      </RadarChart>
    </ResponsiveContainer>
  )
}
