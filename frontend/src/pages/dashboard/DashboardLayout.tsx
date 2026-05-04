import { useState } from 'react'
import { NavLink, Outlet, useNavigate } from 'react-router-dom'
import { BookOpen, LayoutDashboard, Dumbbell, LogOut, Menu, ChevronLeft, ChevronRight } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { ThemeToggle } from '@/components/layout/ThemeToggle'
import { useAuthStore } from '@/stores/authStore'
import { authApi } from '@/api/auth'
import { cn } from '@/lib/utils'

const NAV_ITEMS = [
  { to: '/dashboard', icon: LayoutDashboard, label: 'Dashboard' },
  { to: '/practice',  icon: Dumbbell,        label: 'Practice'  },
]

function getGreeting() {
  const h = new Date().getHours()
  if (h < 12) return 'morning'
  if (h < 17) return 'afternoon'
  return 'evening'
}

export function DashboardLayout() {
  const [open, setOpen] = useState(false)
  const [isCollapsed, setIsCollapsed] = useState(false)
  const { user, clearAuth } = useAuthStore()
  const navigate = useNavigate()

  const handleLogout = async () => {
    try { await authApi.logout() } catch { /* best-effort */ }
    clearAuth()
    navigate('/login')
  }

  const initial  = user?.name?.charAt(0).toUpperCase() ?? '?'
  const greeting = getGreeting()

  return (
    <div className="flex h-dvh overflow-hidden bg-background">
      {/* Mobile backdrop */}
      {open && (
        <div
          className="fixed inset-0 z-20 bg-foreground/25 backdrop-blur-sm lg:hidden"
          onClick={() => setOpen(false)}
        />
      )}

      {/* ── Sidebar ── */}
      <aside
        className={cn(
          'fixed inset-y-0 left-0 z-30 flex flex-col bg-card border-r border-border',
          'transition-all duration-300 ease-in-out',
          isCollapsed ? 'w-20' : 'w-64',
          'lg:static lg:translate-x-0',
          open ? 'translate-x-0' : '-translate-x-full',
        )}
      >
        {/* Brand & Toggle */}
        <div className={cn("flex h-16 shrink-0 items-center border-b border-border relative", isCollapsed ? "justify-center px-0" : "px-5 gap-3")}>
          <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary shadow-md shadow-primary/30">
            <BookOpen className="h-4 w-4 text-primary-fg" />
          </div>
          
          <div className={cn("min-w-0 transition-all duration-300 overflow-hidden whitespace-nowrap", isCollapsed ? "w-0 opacity-0" : "w-32 opacity-100")}>
            <p className="truncate text-sm font-semibold text-foreground">PTE Practice</p>
            <p className="text-xs text-muted-fg">AI-Powered Learning</p>
          </div>

          {/* Desktop Collapse Toggle */}
          <Button
            variant="ghost"
            size="icon"
            onClick={() => setIsCollapsed(!isCollapsed)}
            className="hidden lg:flex absolute -right-3 top-1/2 -translate-y-1/2 h-6 w-6 rounded-full border border-border bg-card shadow-sm hover:bg-secondary text-muted-fg"
          >
            {isCollapsed ? <ChevronRight className="h-3 w-3" /> : <ChevronLeft className="h-3 w-3" />}
          </Button>
        </div>

        {/* Navigation */}
        <nav className="flex-1 space-y-1 overflow-y-auto p-3">
          <p className={cn("px-3 text-[10px] font-semibold uppercase tracking-widest text-muted-fg transition-all duration-300 overflow-hidden whitespace-nowrap", isCollapsed ? "h-0 opacity-0 mb-0" : "h-4 opacity-100 mb-3")}>
            Menu
          </p>
          
          {NAV_ITEMS.map(({ to, icon: Icon, label }) => (
            <NavLink
              key={to}
              to={to}
              end={to === '/dashboard'}
              onClick={() => setOpen(false)}
              title={isCollapsed ? label : undefined}
              className={({ isActive }) =>
                cn(
                  'flex items-center rounded-lg py-2.5 text-sm font-medium transition-all group',
                  isCollapsed ? 'justify-center px-0' : 'gap-3 px-3',
                  isActive
                    ? 'bg-primary/10 text-primary ring-1 ring-primary/20'
                    : 'text-muted-fg hover:bg-secondary hover:text-foreground',
                )
              }
            >
              <Icon className={cn("shrink-0 transition-all duration-300", isCollapsed ? "h-5 w-5" : "h-4 w-4")} />
              <span className={cn("truncate transition-all duration-300 overflow-hidden whitespace-nowrap", isCollapsed ? "max-w-0 opacity-0" : "max-w-37.5 opacity-100")}>
                {label}
              </span>
            </NavLink>
          ))}
        </nav>

        {/* User footer */}
        <div className="border-t border-border p-3">
          <div className={cn("flex items-center rounded-xl bg-secondary/60 py-2.5 transition-all duration-300", isCollapsed ? "justify-center px-0" : "px-3")}>
            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary/20 text-xs font-bold text-primary ring-2 ring-primary/10">
              {initial}
            </div>
            <div className={cn("flex items-center transition-all duration-300 overflow-hidden whitespace-nowrap", isCollapsed ? "w-0 opacity-0" : "w-35 opacity-100 ml-3")}>
              <div className="min-w-0 flex-1">
                <p className="truncate text-sm font-medium text-foreground">{user?.name}</p>
                <p className="truncate text-xs text-muted-fg">{user?.email}</p>
              </div>
              <Button
                variant="ghost"
                size="icon"
                onClick={handleLogout}
                aria-label="Log out"
                className="h-8 w-8 shrink-0 text-muted-fg hover:text-destructive ml-2"
              >
                <LogOut className="h-4 w-4" />
              </Button>
            </div>
          </div>
        </div>
      </aside>

      {/* ── Main column ── */}
      <div className="flex flex-1 flex-col overflow-hidden">
        {/* Top bar */}
        <header className="flex h-16 shrink-0 items-center justify-between border-b border-border bg-card/60 px-4 backdrop-blur-md">
          <div className="flex items-center gap-3">
            <Button
              variant="ghost"
              size="icon"
              onClick={() => setOpen(true)}
              className="lg:hidden"
              aria-label="Open sidebar"
            >
              <Menu className="h-5 w-5" />
            </Button>
            <p className="hidden text-sm text-muted-fg sm:block">
              Good {greeting},{' '}
              <span className="font-semibold text-foreground">{user?.name?.split(' ')[0]}</span>!
            </p>
          </div>
          <ThemeToggle />
        </header>

        {/* Page content */}
        <main className="flex-1 overflow-y-auto p-6">
          <Outlet />
        </main>
      </div>
    </div>
  )
}
