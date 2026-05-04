import { useState } from 'react'
import { Link } from 'react-router-dom'
import { useTasks } from '@/hooks/useTasks'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Skeleton } from '@/components/ui/skeleton'
import { QUESTION_TYPE_LABELS } from '@/lib/constants'
import type { Section } from '@/types/enums'

const SECTIONS: { value: Section | ''; label: string }[] = [
  { value: '',          label: 'All' },
  { value: 'speaking',  label: 'Speaking' },
  { value: 'writing',   label: 'Writing' },
  { value: 'reading',   label: 'Reading' },
  { value: 'listening', label: 'Listening' },
]

const DIFFICULTY_LABELS: Record<number, string>  = { 1: 'Easy', 2: 'Easy+', 3: 'Medium', 4: 'Hard-', 5: 'Hard' }
const DIFFICULTY_COLORS: Record<number, string>  = {
  1: 'text-emerald-500', 2: 'text-blue-500', 3: 'text-amber-500',
  4: 'text-orange-500',  5: 'text-red-500',
}

export function TaskListPage() {
  const [section, setSection] = useState<Section | ''>('')

  const { data, isLoading } = useTasks(section ? { section } : {})
  const tasks = data?.data ?? []

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold">Practice Tasks</h1>
        <p className="mt-1 text-sm text-muted-fg">Choose a task to start practicing.</p>
      </div>

      <div className="flex flex-wrap gap-2">
        {SECTIONS.map((s) => (
          <Button
            key={s.value}
            variant={section === s.value ? 'default' : 'outline'}
            size="sm"
            onClick={() => setSection(s.value)}
          >
            {s.label}
          </Button>
        ))}
      </div>

      {isLoading ? (
        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {Array.from({ length: 6 }, (_, i) => <Skeleton key={i} className="h-36" />)}
        </div>
      ) : tasks.length === 0 ? (
        <p className="text-sm text-muted-fg">No tasks found for this section.</p>
      ) : (
        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {tasks.map((task) => (
            <Link key={task.id} to={`/practice/${task.id}`} className="group">
              <Card className="h-full transition-shadow group-hover:shadow-md">
                <CardHeader className="pb-2">
                  <div className="flex items-start justify-between gap-2">
                    <CardTitle className="text-sm leading-snug">{task.title}</CardTitle>
                    <Badge variant="secondary" className="shrink-0 capitalize">{task.section}</Badge>
                  </div>
                  <CardDescription>
                    {QUESTION_TYPE_LABELS[task.question_type] ?? task.question_type}
                  </CardDescription>
                </CardHeader>
                <CardContent>
                  <div className="flex items-center justify-between text-xs">
                    <span className={DIFFICULTY_COLORS[task.difficulty] ?? 'text-muted-fg'}>
                      {DIFFICULTY_LABELS[task.difficulty] ?? `Level ${task.difficulty}`}
                    </span>
                    <span className="text-muted-fg">{task.estimated_duration_s}s</span>
                  </div>
                </CardContent>
              </Card>
            </Link>
          ))}
        </div>
      )}
    </div>
  )
}
