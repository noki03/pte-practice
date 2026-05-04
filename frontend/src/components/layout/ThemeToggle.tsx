import { Moon, Sun } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { useAuthStore } from '@/stores/authStore'

export function ThemeToggle() {
  const user      = useAuthStore((s) => s.user)
  const updateUser = useAuthStore((s) => s.updateUser)
  const isDark    = user?.ui_preferences?.dark_mode ?? false

  const toggle = () => {
    updateUser({ ui_preferences: { ...user?.ui_preferences, dark_mode: !isDark } })
  }

  return (
    <Button variant="ghost" size="icon" onClick={toggle} aria-label="Toggle theme">
      {isDark ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
    </Button>
  )
}
