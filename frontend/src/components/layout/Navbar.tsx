import { Link, useNavigate } from 'react-router-dom'
import { LogOut, BookOpen } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { ThemeToggle } from './ThemeToggle'
import { useAuthStore } from '@/stores/authStore'
import { authApi } from '@/api/auth'

export function Navbar() {
  const { user, clearAuth } = useAuthStore()
  const navigate            = useNavigate()

  const handleLogout = async () => {
    try { await authApi.logout() } catch { /* ignore */ }
    clearAuth()
    navigate('/login')
  }

  return (
    <header className="sticky top-0 z-40 border-b border-border bg-background/80 backdrop-blur-sm">
      <div className="mx-auto flex h-14 max-w-6xl items-center justify-between px-4">
        <Link to="/dashboard" className="flex items-center gap-2 font-semibold text-foreground">
          <BookOpen className="h-5 w-5 text-primary" />
          PTE Practice
        </Link>

        <nav className="hidden md:flex items-center gap-1">
          <Button variant="ghost" size="sm" asChild>
            <Link to="/dashboard">Dashboard</Link>
          </Button>
          <Button variant="ghost" size="sm" asChild>
            <Link to="/practice">Practice</Link>
          </Button>
        </nav>

        <div className="flex items-center gap-2">
          <ThemeToggle />
          {user && (
            <div className="flex items-center gap-3">
              <span className="hidden sm:block text-sm text-muted-fg">{user.name}</span>
              <Button variant="ghost" size="icon" onClick={handleLogout} aria-label="Logout">
                <LogOut className="h-4 w-4" />
              </Button>
            </div>
          )}
        </div>
      </div>
    </header>
  )
}
