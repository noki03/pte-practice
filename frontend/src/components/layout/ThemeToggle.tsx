import { useEffect, useState } from 'react'
import { Moon, Sun } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { useAuthStore } from '@/stores/authStore'

export function ThemeToggle() {
  const { user, updateUser } = useAuthStore()
  const [isDark, setIsDark] = useState(
    () => document.documentElement.classList.contains('dark'),
  )

  useEffect(() => {
    document.documentElement.classList.toggle('dark', isDark)
  }, [isDark])

  const toggle = () => {
    const next = !isDark
    setIsDark(next)
    if (user) {
      updateUser({ ui_preferences: { ...user.ui_preferences, dark_mode: next } })
    }
  }

  return (
    <Button variant="ghost" size="icon" onClick={toggle} aria-label="Toggle theme">
      {isDark ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
    </Button>
  )
}
